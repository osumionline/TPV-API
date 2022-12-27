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
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';

		if (!$silent) {
			echo "Creo ticket de venta ".$venta->get('id')."\n";
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
		}
		else {
			$route = $this->getConfig()->getDir('web').'ticket.html';
			$route_pdf = $this->getConfig()->getDir('web').($regalo ? '-regalo' : '').'ticket.pdf';
		}
		if (file_exists($route)) {
			unlink($route);
		}
		if (file_exists($route_pdf)) {
			unlink($route_pdf);
		}
		if (!$silent) {
			echo "RUTA HTML: ".$route."\n";
			echo "RUTA PDF: ".$route_pdf."\n";
		}

		$social = $app_data->getSocial();
		for ($i=0; $i<count($social); $i++) {
			$social[$i][0] = OTools::fileToBase64($this->getConfig()->getDir('web').'/iconos/icono_'.$social[$i][0].'.png');
		}

		$ticket_data = [
			'url_base'   => $this->getConfig()->getUrl('base'),
			'logo'       => OTools::fileToBase64($this->getConfig()->getDir('web').'/logo.jpg'),
			'direccion'  => $app_data->getDireccion(),
			'telefono'   => $app_data->getTelefono(),
			'nif'        => $app_data->getCif(),
			'social'     => $social,
			'id'         => $venta->get('id'),
			'date'       => $venta->get('created_at', 'd/m/Y'),
			'hour'       => $venta->get('created_at', 'H:i'),
			'lineas'     => $venta->getLineas(),
			'total'      => $venta->get('total'),
			'forma_pago' => $venta->getNombreTipoPago(),
			'cliente'    => $venta->getCliente(),
			'employee'   => $venta->getEmpleado()->get('nombre'),
			'regalo'     => $regalo
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
		$dompdf->set_paper([0, 0, 147.40, $GLOBALS['bodyHeight'] + 10]);
		$dompdf->loadHtml($html);
		$dompdf->render();

		$output = $dompdf->output();
		file_put_contents($route_pdf, $output);

		return $route_pdf;
	}
}
