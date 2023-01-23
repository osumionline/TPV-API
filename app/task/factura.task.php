<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Model\Factura;
use OsumiFramework\App\Service\imprimirService;

class facturaTask extends OTask {
	private ?imprimirService $imprimir_service = null;

  function __construct() {
		$this->imprimir_service = new imprimirService();
  }

	public function __toString() {
		return "factura: Genera el PDF de la factura indicada.";
	}

	public function run(array $options=[]): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la factura de la que generar el PDF.\n\n";
			echo "  ofw factura 1\n\n";
			exit();
		}

		$id = $options[0];

		$factura = new Factura();
		if (!$factura->find(['id' => $id])) {
			echo "\nERROR: No se ha encontrado la factura indicada.\n\n";
			exit();
		}

		$route_pdf = $this->imprimir_service->generateFactura($factura, false);
		echo "ROUTE PDF: ".$route_pdf."\n\n";
	}
}
