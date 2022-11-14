<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/delete-tipo-pago',
	services: ['general']
)]
class deleteTipoPagoAction extends OAction {
	/**
	 * Función para borrar un tipo de pago
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			if (!$this->general_service->deleteTipoPago($id)) {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
