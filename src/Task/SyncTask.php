<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\Service\SyncService;

class SyncTask extends OTask {
	private ?SyncService $sync_service = null;

	function __construct() {
		$this->sync_service = inject(SyncService::class);
	}

	public function __toString() {
		return "sync: Tarea para realizar la sincronización con la web";
	}

	public function run(array $options = []): void {
		echo $this->sync_service->syncStock();
	}
}
