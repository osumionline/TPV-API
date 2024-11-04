<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiEmpleados\DeleteEmpleado;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\EmpleadosService;

class DeleteEmpleadoComponent extends OComponent {
  private ?EmpleadosService $es = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
		$this->es = inject(EmpleadosService::class);
  }

	/**
	 * Función para borrar un empleado
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			if (!$this->es->deleteEmpleado($id)) {
				$this->status = 'error';
			}
		}
	}
}
