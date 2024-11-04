<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiArticulos\GetAccesosDirectos;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ArticulosService;
use Osumi\OsumiFramework\App\Component\Api\AccesosDirectosList\AccesosDirectosListComponent;

class GetAccesosDirectosComponent extends OComponent {
  private ?ArticulosService $ars = null;

  public ?AccesosDirectosListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->ars = inject(ArticulosService::class);
  }

	/**
	 * Función para obtener la lista de accesos directos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new AccesosDirectosListComponent(['list' => $this->ars->getAccesosDirectos()]);
	}
}
