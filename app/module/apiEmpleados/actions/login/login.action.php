<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Empleado;

#[OModuleAction(
	url: '/login'
)]
class loginAction extends OAction {
	/**
	 * Función para iniciar sesión un empleado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$pass = $req->getParamString('pass');

		if (is_null($id) || is_null($pass)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$empleado = new Empleado();
			if (!$empleado->login($id, $pass)) {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}