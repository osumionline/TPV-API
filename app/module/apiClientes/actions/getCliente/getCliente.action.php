<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\ClienteComponent;
use OsumiFramework\App\Model\Cliente;

#[OModuleAction(
	url: '/get-cliente'
)]
class getClienteAction extends OAction {
	/**
	 * Función para obtener los detalles de un cliente concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$cliente_component = new ClienteComponent(['cliente' => null]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$cliente = new Cliente();
			if ($cliente->find(['id' => $id])) {
				$cliente_component->setValue('cliente', $cliente);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('cliente', $cliente_component);
	}
}
