<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Service\categoriasService;

#[ORoute(
	type: 'json',
	prefix: '/api-categorias'
)]
class apiCategorias extends OModule {
  private ?categoriasService $categorias_service = null;

  function __construct() {
		$this->categorias_service = new categoriasService();
  }

  /**
	 * Función para obtener la lista de categorías
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-categorias')]
	public function getCategorias(ORequest $req): void {
		$list = $this->categorias_service->getCategoryTree([]);

		$this->getTemplate()->addComponent('list', 'api/categorias_list', ['list' => $list, 'extra'=>'nourlencode']);
	}
}
