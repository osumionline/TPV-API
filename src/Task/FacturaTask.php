<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\Factura;
use Osumi\OsumiFramework\App\Service\ImprimirService;

class FacturaTask extends OTask {
	private ?ImprimirService $imprimir_service = null;

	function __construct() {
		$this->imprimir_service = inject(ImprimirService::class);
	}

	public function __toString() {
		return "factura: Genera el PDF de la factura indicada.";
	}

	public function run(array $options = []): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la factura de la que generar el PDF.\n\n";
			echo "  ofw factura --id 1\n\n";
			exit();
		}

		$id = $options['id'];

		$factura = Factura::findOne(['id' => $id]);
		if (is_null($factura)) {
			echo "\nERROR: No se ha encontrado la factura indicada.\n\n";
			exit();
		}

		$route_pdf = $this->imprimir_service->generateFactura($factura, false);
	}
}
