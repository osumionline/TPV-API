<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Service\ticketService;

class ticketTask extends OTask {
	private ?ticketService $ticket_service = null;

  function __construct() {
		$this->ticket_service = new ticketService();
  }

	public function __toString() {
		return "ticket: Genera el PDF del ticket de la venta indicada.";
	}

	public function run(array $options=[]): void {
		if (count($options) != 1) {
			echo "\nERROR: Tienes que indicar el id de la venta de la que generar el ticket.\n\n";
			echo "  ofw ticket 1\n\n";
			exit();
		}

		$id = $options[0];

		$venta = new Venta();
		if (!$venta->find(['id' => $id])) {
			echo "\nERROR: No se ha encontrado la venta indicada.\n\n";
			exit();
		}

		$this->ticket_service->generateTicket($venta, false);
	}
}
