<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCategorias\GetCategorias;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\CategoriasService;
use Osumi\OsumiFramework\App\Component\Api\CategoriasList\CategoriasListComponent;

class GetCategoriasComponent extends OComponent {
  private ?CategoriasService $cs = null;

  public ?CategoriasListComponent $list = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(CategoriasService::class);
  }

	/**
	 * Función para obtener la lista de categorías
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$this->list = new CategoriasListComponent(['list' => $this->cs->getCategoryTree([])]);
	}
}
