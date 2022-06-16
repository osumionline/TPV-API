<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\CategoriasListComponent;

#[OModuleAction(
	url: '/get-categorias',
	services: ['categorias'],
	components: ['api/categorias_list']
)]
class getCategoriasAction extends OAction {
	/**
	 * Función para obtener la lista de categorías
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$categorias_list_component = new CategoriasListComponent(['list' => $this->categorias_service->getCategoryTree([])]);

		$this->getTemplate()->add('list', $categorias_list_component);
	}
}