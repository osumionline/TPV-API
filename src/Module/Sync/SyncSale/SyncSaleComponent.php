<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Sync\SyncSale;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\SyncService;

class SyncSaleComponent extends OComponent {
  private ?SyncService $ss = null;

  public string $status = 'ok';
  public string $items  = '';

  public function __construct() {
    parent::__construct();
    $this->ss = inject(SyncService::class);
  }

	/**
	 * Función para sincronizar una venta de la web en el TPV
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$token  = $req->getParamString('token');

		if (is_null($token)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->items = json_encode($this->ss->updateStock($token));
		}
	}
}
