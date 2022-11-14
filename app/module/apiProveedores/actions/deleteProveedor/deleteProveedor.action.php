<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/delete-proveedor',
	services: ['proveedores']
)]
class deleteProveedorAction extends OAction {
	/**
	 * Función para borrar un proveedor
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
			if (!$this->proveedores_service->deleteProveedor($id)) {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
