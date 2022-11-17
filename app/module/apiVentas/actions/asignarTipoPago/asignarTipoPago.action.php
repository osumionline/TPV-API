<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Venta;

#[OModuleAction(
	url: '/asignar-tipo-pago'
)]
class asignarTipoPagoAction extends OAction {
	/**
	 * Función para asignar un tipo de pago a una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status       = 'ok';
		$id           = $req->getParamInt('id');
		$id_tipo_pago = $req->getParamInt('idTipoPago');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$venta = new Venta();
			if ($venta->find(['id' => $id])) {
				$venta->set('id_tipo_pago', $id_tipo_pago);
				$venta->save();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
