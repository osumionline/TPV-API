<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Model\Reserva;
use OsumiFramework\App\Service\ventasService;
use OsumiFramework\App\Service\imprimirService;

class pruebaTask extends OTask {
	private ?ventasService   $ventas_service = null;
	private ?imprimirService $imprimir_service = null;

	function __construct() {
		$this->ventas_service   = new ventasService();
		$this->imprimir_service = new imprimirService();
	}

	public function __toString() {
		return "prueba: Tarea para pruebas y experimentos";
	}

	public function run(array $options=[]): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la reserva de la que generar el ticket.\n\n";
			echo "  ofw prueba 1\n\n";
			exit();
		}

		$id = $options[0];

		$reserva = new Reserva();
		if (!$reserva->find(['id' => $id])) {
			echo "\nERROR: No se ha encontrado la reserva indicada.\n\n";
			exit();
		}

		$venta = $this->ventas_service->getVentaFromReserva($reserva);
		$ticket_pdf = $this->imprimir_service->generateTicket($venta, 'reserva', false);
	}
}
