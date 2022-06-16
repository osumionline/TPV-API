<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Utils\PDF;
use OsumiFramework\App\Utils\AppData;

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
	 * @return void
	 */
	public function generateTicket(Venta $venta): void {
		require_once $this->getConfig()->getDir('ofw_lib').'fpdf/fpdf.php';
		require_once $this->getConfig()->getDir('app_utils').'PDF.php';
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';

		echo "Creo ticket de venta ".$venta->get('id')."\n";

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		$route = $this->getConfig()->getDir('web').'prueba.pdf';
		if (file_exists($route)) {
			unlink($route);
		}
		echo "RUTA: ".$route."\n";

		$size_ticket = [79, 200];

		$pdf = new PDF('P', 'mm', $size_ticket);
		$pdf->setLogo($this->getConfig()->getDir('web').'logo.jpeg');
		$pdf->setRutaIconos($this->getConfig()->getDir('web').'iconos/');
		$pdf->ticket($venta, $route, $app_data);
	}
}
