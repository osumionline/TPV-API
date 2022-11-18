<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\PagoCaja;

#[OModuleAction(
	url: '/delete-salida-caja'
)]
class deleteSalidaCajaAction extends OAction {
	/**
	 * Función para borrar una salida de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status  = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$pc = new PagoCaja();
			if ($pc->find(['id' => $id])) {
				$pc->delete();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',  $status);
	}
}
