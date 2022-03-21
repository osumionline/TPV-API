<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\App\Model\Venta;

class ticketService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	public function generateTicket(Venta $venta): void {
		require_once($this->getConfig()->getDir('ofw_lib').'fpdf/fpdf.php');
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

		$ruta = $this->getConfig()->getDir('ofw_export').'prueba.pdf';
		if (file_exists($ruta)) {
			unlink($ruta);
		}
		echo "RUTA: ".$ruta."\n";

		$pdf = new \FPDF();
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(40,10,iconv('UTF-8', 'windows-1252', '¡Hola, Mundo!'));
		$pdf->Output('F', $ruta, true);
	}
}
