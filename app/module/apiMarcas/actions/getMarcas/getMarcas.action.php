<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\MarcaListComponent;

#[OModuleAction(
	url: '/get-marcas',
	services: ['marcas']
)]
class getMarcasAction extends OAction {
	/**
	 * Función para obtener la lista de marcas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$marca_list_component = new MarcaListComponent(['list' => $this->marcas_service->getMarcas()]);

		$this->getTemplate()->add('list', $marca_list_component);
	}
}