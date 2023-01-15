<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\OFW\Plugins\OTicketBai;
use OsumiFramework\App\Service\ticketService;

class pruebaTask extends OTask {
	private ?ticketService $ticket_service = null;

	function __construct() {
		$this->ticket_service = new ticketService();
	}

	public function __toString() {
		return "prueba: Tarea para pruebas y experimentos";
	}

	public function run(array $options=[]): void {
		if (count($options) < 1) {
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

		$tbai = new OTicketBai(false);
		var_dump($tbai);
		echo "----------------\n\n";

		if ($tbai->checkStatus()) {
			echo "STATUS TBAI: OK\n";
			$response = $tbai->nuevoTbai($venta->getDatosTBai());
			var_dump($response);
			echo "----------------\n\n";
			if (is_array($response)) {
				$venta->set('tbai_huella', $response['huella_tbai']);
				$venta->set('tbai_qr',     $response['qr']);
				$venta->set('tbai_url',    $response['url']);
				$venta->save();

				echo "VENTA ACTUALIZADA CON DATOS TICKET BAI.\n";

				$this->ticket_service->generateTicket($venta, false, false);
			}
			else {
				echo "OCURRIÓ UN ERROR:\n";
				echo $response;
				echo "\n";
			}
		}
		else {
			echo "STATUS TBAI: ERROR\n";
		}
	}
}
