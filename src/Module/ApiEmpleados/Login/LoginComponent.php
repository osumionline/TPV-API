<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiEmpleados\Login;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\EmpleadosService;

class LoginComponent extends OComponent {
  private ?EmpleadosService $es = null;
  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->es = inject(EmpleadosService::class);
  }

	/**
	 * Función para iniciar sesión un empleado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id   = $req->getParamInt('id');
		$pass = $req->getParamString('pass');

		if (is_null($id) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$empleado = $this->es->login($id, $pass);
			if (is_null($empleado)) {
				$this->status = 'error';
			}
		}
	}
}
