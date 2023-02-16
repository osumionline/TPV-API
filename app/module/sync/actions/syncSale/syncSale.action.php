<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/sale',
	services: ['sync']
)]
class syncSaleAction extends OAction {
	/**
	 * Función para sincronizar una venta de la web en el TPV
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$token  = $req->getParamString('token');
		$items  = [];

		if (is_null($token)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$items = $this->sync_service->updateStock($token);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('items', json_encode($items), 'nourlencode');
	}
}
