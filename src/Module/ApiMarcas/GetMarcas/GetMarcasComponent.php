<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiMarcas\GetMarcas;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\MarcasService;
use Osumi\OsumiFramework\App\Component\Model\MarcaList\MarcaListComponent;

class GetMarcasComponent extends OComponent {
  private ?MarcasService $ms = null;

  public ?MarcaListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->ms = inject(MarcasService::class);
  }

	/**
	 * Función para obtener la lista de marcas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new MarcaListComponent(['list' => $this->ms->getMarcas()]);
	}
}
