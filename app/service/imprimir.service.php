<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\Factura;
use OsumiFramework\App\Utils\PDF;
use OsumiFramework\App\Utils\AppData;
use OsumiFramework\App\Component\Imprimir\TicketComponent;
use OsumiFramework\App\Component\Imprimir\FacturaEmailComponent;

class imprimirService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Función para generar el ticket de una venta
	 *
	 * @param Venta $venta Objeto venta con todos los datos de la venta
	 *
	 * @param bool $regalo Indica si es un ticket regalo
	 *
	 * @param bool $silent Indica si deben mostrarse mensajes, sirve para la tarea ticket
	 *
	 * @return string Ruta al archivo PDF generado
	 */
	public function generateTicket(Venta $venta, bool $regalo = false, bool $silent = true): string {
		require_once $this->getConfig()->getDir('ofw_lib').'dompdf/autoload.inc.php';
		require_once $this->getConfig()->getDir('ofw_lib').'phpqrcode/qrlib.php';
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';

		if (!$silent) {
			echo "Creo ticket de venta ".$venta->get('id')."\n";
			if ($regalo) {
				echo "TICKET REGALO\n";
			}
			echo "\n";
		}

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		if ($silent) {
			$route = $this->getConfig()->getDir('ofw_tmp').'ticket_'.$venta->get('id').'.html';
			$route_pdf = $this->getConfig()->getDir('ofw_tmp').'ticket_'.$venta->get('id').($regalo ? '-regalo' : '').'.pdf';
			$route_qr = $this->getConfig()->getDir('ofw_tmp').'ticket_'.$venta->get('id').($regalo ? '-qr' : '').'.png';
		}
		else {
			$route = $this->getConfig()->getDir('web').'ticket.html';
			$route_pdf = $this->getConfig()->getDir('web').'ticket'.($regalo ? '-regalo' : '').'.pdf';
			$route_qr = $this->getConfig()->getDir('web').'ticket'.($regalo ? '-regalo' : '').'-qr.png';
		}
		if (file_exists($route)) {
			unlink($route);
		}
		if (file_exists($route_pdf)) {
			unlink($route_pdf);
		}
		if (file_exists($route_qr)) {
			unlink($route_qr);
		}
		if (!$silent) {
			echo "RUTA HTML: ".$route."\n";
			echo "RUTA PDF: ".$route_pdf."\n";
			echo "RUTA QR: ".$route_qr."\n";
		}

		$social = $app_data->getSocial();
		for ($i=0; $i<count($social); $i++) {
			$social[$i][0] = OTools::fileToBase64($this->getConfig()->getDir('web').'/iconos/icono_'.$social[$i][0].'.png');
		}

		$ivas = [];
		foreach ($venta->getLineas() as $linea) {
			if (!array_key_exists('iva_'.$linea->get('iva'), $ivas)) {
				$ivas['iva_'.$linea->get('iva')] = [
					'iva' => $linea->get('iva'),
					'base' => 0,
					'cuota_iva' => 0
				];
			}
			$importe = ($linea->get('importe') < 0) ? $linea->get('importe') * -1 : $linea->get('importe');
			$base = $importe / (($linea->get('iva') / 100) +1);
			$ivas['iva_'.$linea->get('iva')]['base'] += $base;
			$ivas['iva_'.$linea->get('iva')]['cuota_iva'] += $importe - $base;
		}

		\QRcode::png(strval(-1 * $venta->get('id')), $route_qr);
		$qr = OTools::fileToBase64($route_qr);

		$ticket_data = [
			'url_base'       => $this->getConfig()->getUrl('base'),
			'logo'           => OTools::fileToBase64($this->getConfig()->getDir('web').'/logo.jpg'),
			'direccion'      => $app_data->getDireccion(),
			'telefono'       => $app_data->getTelefono(),
			'nif'            => $app_data->getCif(),
			'social'         => $social,
			'num_venta'      => $venta->get('num_venta'),
			'date'           => $venta->get('created_at', 'd/m/Y'),
			'hour'           => $venta->get('created_at', 'H:i'),
			'lineas'         => $venta->getLineas(),
			'total'          => $venta->get('total'),
			'mixto'          => $venta->get('pago_mixto'),
			'entregado'      => $venta->get('entregado'),
			'entregado_otro' => $venta->get('entregado_otro'),
			'id_tipo_pago'   => $venta->get('id_tipo_pago'),
			'forma_pago'     => $venta->getNombreTipoPago(),
			'cliente'        => $venta->getCliente(),
			'employee'       => $venta->getEmpleado()->get('nombre'),
			'regalo'         => $regalo,
			'ivas'           => $ivas,
			'qr'             => $qr,
			'tbai_qr'        => $venta->get('tbai_qr'),
			'tbai_huella'    => $venta->get('tbai_huella')
		];

		$ticket = new TicketComponent(['data' => $ticket_data]);
		$html = strval($ticket);
		file_put_contents($route, $html);

		$dompdf = new \Dompdf\Dompdf();
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
		require_once $this->getConfig()->getDir('ofw_lib').'dompdf/autoload.inc.php';
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		if (!$silent) {
			echo "Creo factura ".$factura->get('id')."\n\n";
		}

		if ($silent) {
			$route = $this->getConfig()->getDir('ofw_tmp').'factura_'.$factura->get('id').'.html';
			$route_pdf = $this->getConfig()->getDir('ofw_tmp').'factura_'.$factura->get('id').'.pdf';
		}
		else {
			$route = $this->getConfig()->getDir('web').'factura.html';
			$route_pdf = $this->getConfig()->getDir('web').'factura.pdf';
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

		foreach ($factura->getVentas() as $venta) {
			$temp = [
				'concepto'       => 'Ticket Nº '.$venta->get('id'),
				'fecha'          => $venta->get('created_at', 'd/m/Y'),
				'total'          => $venta->get('total'),
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
					'concepto'       => $linea->get('nombre_articulo'),
					'precio_iva'     => $linea->get('pvp'),
					'precio_sin_iva' => $linea->get('pvp') / ((100 + $linea->get('iva')) / 100),
					'unidades'       => $linea->get('unidades'),
					'iva'            => $linea->get('iva'),
					'descuento'      => $linea->getTotalDescuento(),
					'total'          => $linea->get('importe')
				];
				$venta_linea['subtotal']    = $linea->get('unidades') * $venta_linea['precio_sin_iva'];
				$venta_linea['iva_importe'] = $linea->get('pvp') * $linea->get('unidades') - $venta_linea['subtotal'];

				$temp['precio_iva']     += $venta_linea['unidades'] * $venta_linea['precio_iva'];
				$temp['precio_sin_iva'] += $venta_linea['unidades'] * $venta_linea['precio_sin_iva'];
				$temp['unidades']       += $venta_linea['unidades'];
				$temp['subtotal']       += $venta_linea['subtotal'];
				$temp['iva_importe']    += $venta_linea['iva_importe'];
				$temp['descuento']      += $venta_linea['descuento'];

				$subtotal  += $venta_linea['subtotal'];
				$descuento += $venta_linea['descuento'];
				$total     += $venta_linea['total'];
				
				array_push($temp['lineas'], $venta_linea);
			}

			array_push($list, $temp);
		}

		$factura_data = [
			'id'                       => $factura->get('id'),
			'logo'                     => OTools::fileToBase64($this->getConfig()->getDir('web').'/logo.jpg'),
			'fecha'                    => $factura->get('created_at', 'd/m/Y'),
			'num_factura'              => $factura->get('num_factura'),
			'factura_year'             => $factura->get('created_at', 'Y'),
			'nombre_comercial'         => $app_data->getNombreComercial(),
			'cif'                      => $app_data->getCif(),
			'cliente_nombre_apellidos' => $factura->get('nombre_apellidos'),
			'cliente_dni_cif'          => $factura->get('dni_cif'),
			'direccion'                => $app_data->getDireccion(),
			'telefono'                 => $app_data->getTelefono(),
			'email'                    => $app_data->getEmail(),
			'cliente_direccion'        => $factura->get('direccion'),
			'cliente_codigo_postal'    => $factura->get('codigo_postal'),
			'cliente_poblacion'	       => $factura->get('poblacion'),
			'list'                     => $list,
			'subtotal'                 => $subtotal,
			'ivas'                     => [],
			'descuento'                => $descuento,
			'total'                    => $total
		];
		
		$factura_email_component = new FacturaEmailComponent($factura_data);
		$html = strval($factura_email_component);
		file_put_contents($route, $html);

		$dompdf = new \Dompdf\Dompdf();

		return $route_pdf;
	}
}
