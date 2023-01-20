<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Factura;

#[OModuleAction(
	url: '/send-factura'
)]
class sendFacturaAction extends OAction {
	/**
	 * Función para enviar por email una factura a un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$factura = new Factura();
			if ($factura->find(['id' => $id])) {
				$cliente = $factura->getCliente();
				if (!is_null($cliente->get('email')) && $cliente->get('email') != '') {
					
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
