<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/delete-empleado',
	services: ['empleados']
)]
class deleteEmpleadoAction extends OAction {
	/**
	 * Función para borrar un empleado
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
			if (!$this->empleados_service->deleteEmpleado($id)) {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
