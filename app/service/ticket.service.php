<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Utils\PDF;
use OsumiFramework\App\Utils\AppData;
use OsumiFramework\App\Component\Ticket\TicketComponent;

class ticketService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 *  Función para obtener los datos de una venta
	 *
	 * @param int $id Id de la venta de la que obtener datos
	 *
	 * @return array Datos de la venta
	 */
	public function getVenta(int $id): array {
		$venta = new Venta();
		if ($venta->find(['id' => $id])) {
			return [
				'efectivo' => $venta->get('entregado'),
				'cambio' =>  $venta->getCambio(),
				'tarjeta' => $venta->get('entregado_otro'),
				'idEmpleado' => $venta->get('id_empleado'),
				'idTipoPago' => $venta->get('id_tipo_pago'),
				'idCliente' => $venta->get('id_cliente'),
				'total' => $venta->get('total'),
				'lineas' => $venta->getLineas(),
				'pagoMixto' => $venta->get('pago_mixto'),
				'factura' => true
			];
		}
		else {
			return [];
		}
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
					're' => $linea->get('re'),
					'base' => 0,
					'cuota_iva' => 0,
					'cuota_re' => 0
				];
			}
			$base = $linea->get('importe') * ((100 - $linea->get('iva') - $linea->get('re')) / 100);
			$ivas['iva_'.$linea->get('iva')]['base'] += $base;
			$ivas['iva_'.$linea->get('iva')]['cuota_iva'] += $base * ($linea->get('iva') / 100);
			$ivas['iva_'.$linea->get('iva')]['cuota_re'] += $base * ($linea->get('re') / 100);
		}

		\QRcode::png(strval($venta->get('id')), $route_qr);
		$qr = OTools::fileToBase64($route_qr);

		$ticket_data = [
			'url_base'       => $this->getConfig()->getUrl('base'),
			'logo'           => OTools::fileToBase64($this->getConfig()->getDir('web').'/logo.jpg'),
			'direccion'      => $app_data->getDireccion(),
			'telefono'       => $app_data->getTelefono(),
			'nif'            => $app_data->getCif(),
			'social'         => $social,
			'id'             => $venta->get('id'),
			'date'           => $venta->get('created_at', 'd/m/Y'),
			'hour'           => $venta->get('created_at', 'H:i'),
			'lineas'         => $venta->getLineas(),
			'total'          => $venta->get('total'),
			'mixto'          => $venta->get('pago_mixto'),
			'entregado'      => $venta->get('entregado'),
			'entregado_otro' => $venta->get('entregado_otro'),
			'forma_pago'     => $venta->getNombreTipoPago(),
			'cliente'        => $venta->getCliente(),
			'employee'       => $venta->getEmpleado()->get('nombre'),
			'regalo'         => $regalo,
			'ivas'           => $ivas,
			'qr'             => $qr
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
		$dompdf->set_paper([0, 0, 147.40, $GLOBALS['bodyHeight'] + 15]);
		$dompdf->loadHtml($html);
		$dompdf->render();

		$output = $dompdf->output();
		file_put_contents($route_pdf, $output);

		return $route_pdf;
	}
}
