<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Service\imprimirService;

class ticketTask extends OTask {
	private ?ticketService $imprimir_service = null;

  function __construct() {
		$this->imprimir_service = new imprimirService();
  }

	public function __toString() {
		return "ticket: Genera el PDF del ticket de la venta indicada.";
	}

	public function run(array $options=[]): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la venta de la que generar el ticket.\n\n";
			echo "  ofw ticket 1\n\n";
			exit();
		}

		$id = $options[0];
		$regalo = array_key_exists(1, $options);

		$venta = new Venta();
		if (!$venta->find(['id' => $id])) {
			echo "\nERROR: No se ha encontrado la venta indicada.\n\n";
			exit();
		}

		$this->imprimir_service->generateTicket($venta, $regalo, false);
	}
}
