<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\Search;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\Component\Api\BuscadorVentasList\BuscadorVentasListComponent;

class SearchComponent extends OComponent {
  private ?ArticulosService $as = null;

  public string $status = 'ok';
  public ?BuscadorVentasListComponent $list = null;

  public function __construct() {
    parent::__construct();
    $this->as = inject(ArticulosService::class);
  }

	/**
	 * Buscador de artículos para venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$q = $req->getParamString('q');
		$this->list = new BuscadorVentasListComponent();

		if (is_null($q)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->list->list = $this->as->searchArticulosVentas($q);
		}
	}
}
