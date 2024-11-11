<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Factura;
use Osumi\OsumiFramework\App\Utils\AppData;
use Osumi\OsumiFramework\App\Component\Imprimir\Ticket\TicketComponent;
use Osumi\OsumiFramework\App\Component\Imprimir\Factura\FacturaComponent;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\Output\QROutputInterface;
use Dompdf\Dompdf;

class ImprimirService extends OService {
	/**
	 * Función para generar el ticket de una venta
	 *
	 * @param Venta $venta Objeto venta con todos los datos de la venta
	 *
	 * @param string $tipo Indica si es un ticket de venta, regalo o reserva
	 *
	 * @param bool $silent Indica si deben mostrarse mensajes, sirve para la tarea ticket
	 *
	 * @return string Ruta al archivo PDF generado
	 */
	public function generateTicket(Venta $venta, string $tipo = 'venta', bool $silent = true): string {
		if (!$silent) {
			echo "Creo ticket de venta {$venta->id} ({$tipo})\n\n";
		}

		OTools::checkOfw('cache');
		OTools::checkOfw('tmp');

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		if ($silent) {
			$folder    = $this->getConfig()->getDir('ofw_tmp') . "venta/{$venta->id}";
			$route     = "{$folder}/ticket_{$venta->id}-{$tipo}.html";
			$route_pdf = "{$folder}/ticket_{$venta->id}-{$tipo}.pdf";
			$route_qr  = "{$folder}/ticket_{$venta->id}-{$tipo}-qr.png";
		}
		else {
			$folder    = $this->getConfig()->getDir('public') . "venta/{$venta->id}";
			$route     = "{$folder}/ticket-{$tipo}.html";
			$route_pdf = "{$folder}/ticket-{$tipo}.pdf";
			$route_qr  = "{$folder}/ticket-{$tipo}-qr.png";
		}
		if (!is_dir($folder)) {
			if (!$silent) {
				echo "LA CARPETA \"{$folder}\" no existe, la creo.\n";
			}
			mkdir($folder, 0777, true);
		}
		if (file_exists($route)) {
			if (!$silent) {
				echo "EL ARCHIVO \"{$route}\" existe, lo borro.\n";
			}
			unlink($route);
		}
		if (file_exists($route_pdf)) {
			if (!$silent) {
				echo "EL ARCHIVO \"{$route_pdf}\" existe, lo borro.\n";
			}
			unlink($route_pdf);
		}
		if (file_exists($route_qr)) {
			if (!$silent) {
				echo "EL ARCHIVO \"{$route_qr}\" existe, lo borro.\n";
			}
			unlink($route_qr);
		}
		$check_images = glob($folder . '/' . pathinfo($route_pdf, PATHINFO_FILENAME) . '_*.jpg');
		if (count($check_images) > 0) {
			foreach ($check_images as $image) {
				if (!$silent) {
					echo "EL ARCHIVO \"{$image}\" existe, lo borro.\n";
				}
				unlink($image);
			}
		}
		if (!$silent) {
			echo "RUTA HTML: {$route}\n";
			echo "RUTA PDF: {$route_pdf}\n";
			echo "RUTA QR: {$route_qr}\n";
		}

		$social = $app_data->getSocial();
		for ($i = 0; $i < count($social); $i++) {
			$social[$i][0] = OTools::fileToBase64($this->getConfig()->getDir('public') . '/iconos/icono_' . $social[$i][0] . '.png');
		}

		$ivas = [];
		foreach ($venta->getLineas() as $linea) {
			if (!array_key_exists('iva_'.$linea->iva, $ivas)) {
				$ivas['iva_'.$linea->iva] = [
					'iva'       => $linea->iva,
					'base'      => 0,
					'cuota_iva' => 0
				];
			}
			$importe = ($linea->importe < 0) ? $linea->importe * -1 : $linea->importe;
			$base = $importe / (($linea->iva / 100) + 1);
			$ivas['iva_'.$linea->iva]['base']      += $base;
			$ivas['iva_'.$linea->iva]['cuota_iva'] += $importe - $base;
		}

		$options = new QROptions;
		$options->outputType = QROutputInterface::GDIMAGE_PNG;
		$options->outputBase64 = false;

		$data = strval(-1 * $venta->id);
		$qrcode  = new QRCode;
		$qrcode->setOptions($options);
		$qrcode->render($data, $route_qr);

		$qr = OTools::fileToBase64($route_qr);

		$ticket_data = [
			'url_base'         => $this->getConfig()->getUrl('base'),
			'logo'             => OTools::fileToBase64($this->getConfig()->getDir('public') . '/logo.jpg'),
			'direccion'        => $app_data->getDireccion(),
			'poblacion'        => $app_data->getPoblacion(),
			'nombre_comercial' => $app_data->getNombreComercial(),
			'telefono'         => $app_data->getTelefono(),
			'nif'              => $app_data->getCif(),
			'social'           => $social,
			'timestamp'        => strtotime($venta->created_at),
			'num_venta'        => $venta->num_venta,
			'date'             => $venta->get('created_at', 'd/m/Y'),
			'hour'             => $venta->get('created_at', 'H:i'),
			'lineas'           => $venta->getLineas(),
			'total'            => $venta->total,
			'mixto'            => $venta->pago_mixto,
			'entregado'        => $venta->entregado,
			'entregado_otro'   => $venta->entregado_otro,
			'id_tipo_pago'     => $venta->id_tipo_pago,
			'forma_pago'       => $venta->getNombreTipoPago(),
			'cliente'          => $venta->getCliente(),
			'employee'         => (!is_null($venta->getEmpleado())) ? $venta->getEmpleado()->nombre : '-',
			'tipo'             => $tipo,
			'ivas'             => $ivas,
			'qr'               => $qr,
			'tbai_qr'          => $venta->tbai_qr,
			'tbai_huella'      => $venta->tbai_huella
		];

		$ticket = new TicketComponent(['data' => $ticket_data]);
		$html = strval($ticket);
		file_put_contents($route, $html);

		$dompdf = new Dompdf();
		$dompdf->setPaper([0.0, 0.0, 147.40, 209.76]);

		$GLOBALS['bodyHeight'] = 0;

		$dompdf->setCallbacks(
			[
				'myCallbacks' => [
					'event' => 'end_frame', 'f' => function ($infos) {
						$frame = $infos->get_frame();
						if (strtolower($frame->get_node()->nodeName) === "body") {
							$padding_box = $frame->get_padding_box();
							$GLOBALS['bodyHeight'] += $padding_box['h'];
						}
					}
				]
			]
		);

		$dompdf->loadHtml($html);
		$dompdf->render();
		unset($dompdf);

		$dompdf = new \Dompdf\Dompdf();
		$dompdf->set_paper([0, 0, 147.40, $GLOBALS['bodyHeight'] + 20]);
		$dompdf->loadHtml($html);
		$dompdf->render();

		$output = $dompdf->output();
		file_put_contents($route_pdf, $output);

		// Convierto el ticket a JPG
		$images = OImage::convertPdfToJpg($route_pdf, $folder);

		if (!$silent) {
			echo "IMAGENES GENERADAS: \n";
			var_dump($images);
		}

		return $route_pdf;
	}

	/**
	 * Función para generar el PDF de una factura
	 *
	 * @param Factura $factura Factura de la que hay que generar el PDF
	 *
	 * @param bool $silent Indica si deben mostrarse mensajes, sirve para la tarea factura
	 */
	public function generateFactura(Factura $factura, bool $silent = true): string {
		OTools::checkOfw('cache');
		OTools::checkOfw('tmp');

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		if (!$silent) {
			echo "Creo factura {$factura->id}\n\n";
		}

		if ($silent) {
			$route     = $this->getConfig()->getDir('ofw_tmp') . "factura_{$factura->id}.html";
			$route_pdf = $this->getConfig()->getDir('ofw_tmp') . "factura_{$factura->id}.pdf";
		}
		else {
			$route     = $this->getConfig()->getDir('public') . "factura_{$factura->id}.html";
			$route_pdf = $this->getConfig()->getDir('public') . "factura_{$factura->id}.pdf";
			echo "RUTA HTML: {$route}\n";
			echo "RUTA PDF: {$route_pdf}\n";
		}
		if (file_exists($route)) {
			unlink($route);
		}
		if (file_exists($route_pdf)) {
			unlink($route_pdf);
		}

		$list      = [];
		$subtotal  = 0;
		$descuento = 0;
		$total     = 0;
		$ivas      = [];

		foreach ($factura->getVentas() as $venta) {
			$temp = [
				'concepto'       => 'Ticket Nº ' . $venta->id,
				'fecha'          => $venta->get('created_at', 'd/m/Y'),
				'total'          => $venta->total,
				'precio_iva'     => 0,
				'precio_sin_iva' => 0,
				'unidades'       => 0,
				'subtotal'       => 0,
				'iva'            => null,
				'iva_importe'    => 0,
				'descuento'      => 0,
				'lineas'         => []
			];

			foreach ($venta->getLineas() as $linea) {
				$venta_linea = [
					'concepto'       => $linea->nombre_articulo,
					'precio_iva'     => $linea->pvp,
					'precio_sin_iva' => $linea->pvp / ((100 + $linea->iva) / 100),
					'unidades'       => $linea->unidades,
					'iva'            => $linea->iva,
					'descuento'      => -1 * $linea->getTotalDescuento(),
					'total'          => $linea->importe
				];
				$venta_linea['subtotal']    = $linea->unidades * $venta_linea['precio_sin_iva'];
				$venta_linea['iva_importe'] = $linea->pvp * $linea->unidades - $venta_linea['subtotal'];

				$temp['precio_iva']     += $venta_linea['unidades'] * $venta_linea['precio_iva'];
				$temp['precio_sin_iva'] += $venta_linea['unidades'] * $venta_linea['precio_sin_iva'];
				$temp['unidades']       += $venta_linea['unidades'];
				$temp['subtotal']       += $venta_linea['subtotal'];
				$temp['iva_importe']    += $venta_linea['iva_importe'];
				$temp['descuento']      += $venta_linea['descuento'];

				$subtotal  += $venta_linea['subtotal'];
				$descuento += $venta_linea['descuento'];
				$total     += $venta_linea['total'];

				if (!array_key_exists('iva_' . $linea->iva, $ivas)) {
					$ivas['iva_' . $linea->iva] = [
						'iva'       => $linea->iva,
						'base'      => 0,
						'cuota_iva' => 0
					];
				}
				$importe = $linea->pvp * $linea->unidades;
				$base = $importe / (($linea->iva / 100) +1);
				$ivas['iva_' . $linea->iva]['base']      += $base;
				$ivas['iva_' . $linea->iva]['cuota_iva'] += $importe - $base;

				$temp['lineas'][] = $venta_linea;
			}

			$list[] = $temp;
		}
		usort($ivas, fn($a, $b) => $a['iva'] - $b['iva']);

		$factura_data = [
			'id'                       => $factura->id,
			'logo'                     => OTools::fileToBase64($this->getConfig()->getDir('public') . '/logo.jpg'),
			'fecha'                    => $factura->get('created_at', 'd/m/Y'),
			'num_factura'              => $factura->num_factura,
			'factura_year'             => $factura->get('created_at', 'Y'),
			'nombre_comercial'         => $app_data->getNombreComercial(),
			'cif'                      => $app_data->getCif(),
			'cliente_nombre_apellidos' => $factura->nombre_apellidos,
			'cliente_dni_cif'          => $factura->dni_cif,
			'direccion'                => $app_data->getDireccion(),
			'telefono'                 => $app_data->getTelefono(),
			'email'                    => $app_data->getEmail(),
			'cliente_direccion'        => $factura->direccion,
			'cliente_codigo_postal'    => $factura->codigo_postal,
			'cliente_poblacion'	       => $factura->poblacion,
			'list'                     => $list,
			'subtotal'                 => $subtotal,
			'ivas'                     => $ivas,
			'descuento'                => $descuento,
			'total'                    => $total
		];

		$factura_component = new FacturaComponent($factura_data);
		$html = strval($factura_component);
		file_put_contents($route, $html);

		$dompdf = new Dompdf();
		$dompdf->set_paper("A4", "portrait");
		$dompdf->loadHtml($html);
		$dompdf->render();

		$output = $dompdf->output();
		file_put_contents($route_pdf, $output);

		return $route_pdf;
	}

	/**
	 * Función para imprimir un ticket previamente generado
	 *
	 * @param string $ticket_pdf Ruta al archivo PDF
	 *
	 * @rreturn void
	 */
	public function imprimirTicket(string $ticket_pdf): void {
		if (PHP_OS_FAMILY == 'Windows') {
			$comando =  '"'.$this->getConfig()->getExtra('foxit').'" -t "'.str_ireplace('/', "\\", $ticket_pdf).'" '.$this->getConfig()->getExtra('impresora');
		}
		else {
			$comando = "lpr -P ".$this->getConfig()->getExtra('impresora')." ".$ticket_pdf." &";
		}
		$this->getLog()->debug($comando);
		exec($comando, $salida);
	}
}
