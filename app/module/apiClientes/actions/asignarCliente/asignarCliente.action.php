<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Venta;

#[OModuleAction(
	url: '/asignar-cliente'
)]
class asignarClienteAction extends OAction {
	/**
	 * Función para asignar (o quitar) un cliente a una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status     = 'ok';
		$id         = $req->getParamInt('id');
		$id_cliente = $req->getParamInt('idCliente');

		if (is_null($id) || is_null($id_cliente)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$venta = new Venta();
			if ($venta->find(['id' => $id])) {
				$venta->set('id_cliente', $id_cliente);
				$venta->save();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}