<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Service\ImprimirService;

class TicketTask extends OTask {
	private ?ImprimirService $imprimir_service = null;

  function __construct() {
		$this->imprimir_service = inject(ImprimirService::class);
  }

	public function __toString() {
		return "ticket: Genera el PDF del ticket de la venta indicada.";
	}

	public function run(array $options = []): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la venta de la que generar el ticket.\n\n";
			echo "  ofw ticket --id 1\n\n";
			exit();
		}

		$id = $options['id'];
		$regalo = array_key_exists('regalo', $options) ? $options['regalo'] : 'venta';

		$venta = Venta::findOne(['id' => $id]);
		if (is_null($venta)) {
			echo "\nERROR: No se ha encontrado la venta indicada.\n\n";
			exit();
		}

		$this->imprimir_service->generateTicket($venta, $regalo, false);
	}
}
