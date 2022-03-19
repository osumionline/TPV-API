<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\Empleado;
use OsumiFramework\App\Service\empleadosService;

#[ORoute(
	type: 'json',
	prefix: '/api-empleados'
)]
class apiEmpleados extends OModule {
	private ?empleadosService $empleados_service = null;

	function __construct() {
		$this->empleados_service = new empleadosService();
	}

	/**
	 * Función para obtener la lista de empleados
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/get-empleados')]
	public function getEmpleados(ORequest $req): void {
		$list = $this->empleados_service->getEmpleados();

		$this->getTemplate()->addComponent('list', 'model/empleado_list', ['list' => $list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para iniciar sesión un empleado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/login')]
	public function login(ORequest $req): void {
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