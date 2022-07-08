<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\ClienteListComponent;

#[OModuleAction(
	url: '/search-clientes',
	services: ['clientes']
)]
class searchClientesAction extends OAction {
	/**
	 * Función para buscar clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$name = $req->getParamString('name');
		$cliente_list_component = new ClienteListComponent(['list' => []]);

		if (is_null($name)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$cliente_list_component->setValue('list', $this->clientes_service->searchClientes($name));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $cliente_list_component);
	}
}