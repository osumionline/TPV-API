<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Utils\PDF;

class ticketService extends OService {
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
	 * @return void
	 */
	public function generateTicket(Venta $venta): void {
		require_once($this->getConfig()->getDir('ofw_lib').'fpdf/fpdf.php');
		require_once($this->getConfig()->getDir('app_utils').'PDF.php');
		echo "Creo ticket de venta ".$venta->get('id')."\n";

		// Cargo archivo de configuración
		$config_file = $this->getConfig()->getDir('ofw_cache')."app_data.json";
		if (!file_exists($config_file)) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio.\n";
			exit();
		}
		$config = json_decode( file_get_contents($config_file), true);
		if (is_null($config)) {
			echo "ERROR: El archivo de configuración no está bien formado.\n";
			exit();
		}

		$route = $this->getConfig()->getDir('ofw_export').'prueba.pdf';
		if (file_exists($route)) {
			unlink($route);
		}
		echo "RUTA: ".$route."\n";

		$size_ticket = [79, 200];

		$pdf = new PDF('P', 'mm', $size_ticket);
		$pdf->setLogo($this->getConfig()->getDir('web').'logo.jpeg');
		$pdf->setRutaIconos($this->getConfig()->getDir('web').'iconos/');
		$pdf->ticket($venta, $route);
	}
}
