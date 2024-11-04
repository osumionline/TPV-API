<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Sync\SyncStock;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\SyncService;

class SyncStockComponent extends OComponent {
	private ?SyncService $ss = null;

  public function __construct() {
    parent::__construct();
    $this->ss = inject(SyncService::class);
  }

  /**
	 * Función para sincronizar el stock del TPV con la web
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		echo $this->ss->syncStock();
		exit;
	}
}
