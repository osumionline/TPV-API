<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\ClienteListComponent;

#[OModuleAction(
	url: '/get-clientes',
	services: ['clientes'],
	components: ['model/cliente_list']
)]
class getClientesAction extends OAction {
	/**
	 * Función para obtener la lista de clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$cliente_list_component = new ClienteListComponent(['list' => $this->clientes_service->getClientes()]);

		$this->getTemplate()->add('status', 'ok');
		$this->getTemplate()->add('list',   $cliente_list_component);
	}
}