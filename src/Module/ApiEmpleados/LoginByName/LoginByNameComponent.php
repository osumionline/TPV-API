<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiEmpleados\LoginByName;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\EmpleadosService;

class LoginByNameComponent extends OComponent {
  private ?EmpleadosService $es = null;
  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
    $this->es = inject(EmpleadosService::class);
  }

	/**
	 * Función para iniciar sesión un empleado, usando su nombre
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$name = $req->getParamString('name');
		$pass = $req->getParamString('pass');

		if (is_null($name) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$empleado = $this->es->loginByName($name, $pass);
			if (is_null($empleado)) {
				$this->status = 'error';
			}
		}
	}
}
