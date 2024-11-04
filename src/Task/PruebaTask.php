<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Model\Reserva;
use Osumi\OsumiFramework\App\Service\VentasService;
use Osumi\OsumiFramework\App\Service\ImprimirService;

class PruebaTask extends OTask {
	private ?VentasService   $ventas_service = null;
	private ?ImprimirService $imprimir_service = null;

	function __construct() {
		$this->ventas_service   = inject(VentasService::class);
		$this->imprimir_service = inject(ImprimirService::class);
	}

	public function __toString() {
		return "prueba: Tarea para pruebas y experimentos";
	}

	public function run(array $options = []): void {
		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id de la reserva de la que generar el ticket.\n\n";
			echo "  ofw prueba --id 1\n\n";
			exit();
		}

		$id = $options['id'];

		$reserva = Reserva::findOne(['id' => $id]);
		if (is_null($reserva)) {
			echo "\nERROR: No se ha encontrado la reserva indicada.\n\n";
			exit();
		}

		$venta      = $this->ventas_service->getVentaFromReserva($reserva);
		$ticket_pdf = $this->imprimir_service->generateTicket($venta, 'reserva', false);
	}
}
