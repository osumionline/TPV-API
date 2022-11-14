<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\TipoPago;

#[OModuleAction(
	url: '/save-tipo-pago-orden'
)]
class saveTipoPagoOrdenAction extends OAction {
	/**
	 * Función para actualizar el orden de los tipos de pago
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$list = $req->getParam('list');

		if (is_null($list)) {
			$status = 'error';
		}

		if ($status=='ok') {
			foreach ($list as $item) {
				$tp = new TipoPago();
				if ($tp->find(['id'=>$item['id']])) {
					$tp->set('orden', $item['orden']);
					$tp->save();
				}
				else {
					$status = 'error';
				}
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
