<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\PedidoComponent;
use OsumiFramework\App\Model\Pedido;

#[OModuleAction(
	url: '/get-pedido'
)]
class getPedidoAction extends OAction {
	/**
	 * Función para obtener el detalle de un pedido
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$pedido_component = new PedidoComponent(['pedido' => null]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$pedido = new Pedido();
			if ($pedido->find(['id' => $id])) {
				$pedido_component->setValue('pedido', $pedido);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('pedido', $pedido_component);
	}
}
