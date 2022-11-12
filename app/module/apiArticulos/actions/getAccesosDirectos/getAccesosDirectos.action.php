<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Api\AccesosDirectosListComponent;

#[OModuleAction(
	url: '/get-accesos-directos',
	services: ['articulos']
)]
class getAccesosDirectosAction extends OAction {
	/**
	 * Función para obtener la lista de accesos directos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$accesos_directos_list_component = new AccesosDirectosListComponent(['list' => $this->articulos_service->getAccesosDirectos()]);
		$this->getTemplate()->add('list', $accesos_directos_list_component);
	}
}
