<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Service\clientesService;

class pruebaTask extends OTask {
	private ?clientesService $clientes_service = null;

	function __construct() {
		$this->clientes_service = new clientesService();
  }

	public function __toString() {
		return "prueba: Tarea para pruebas y experimentos";
	}

	public function run(array $options=[]): void {
		$num = $this->clientes_service->generateNumFactura();
		echo "NUM: ".$num."\n";
	}
}
