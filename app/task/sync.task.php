<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Service\syncService;

class syncTask extends OTask {
	private ?syncService $sync_service = null;

	function __construct() {
		$this->sync_service = new syncService();
	}

	public function __toString() {
		return "sync: Tarea para realizar la sincronización con la web";
	}

	public function run(array $options=[]): void {
		echo $this->sync_service->syncStock();
	}
}