<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiEmpleados\SaveEmpleado;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\EmpleadosService;
use Osumi\OsumiFramework\App\DTO\EmpleadoDTO;
use Osumi\OsumiFramework\App\Model\Empleado;

class SaveEmpleadoComponent extends OComponent {
  private ?EmpleadosService $es = null;

  public string $status = 'ok';

  public function __construct() {
    parent::__construct();
		$this->es = inject(EmpleadosService::class);
  }

	/**
	 * Función para guardar los datos de un empleado
	 *
	 * @param EmpleadoDTO $data Objeto con toda la información sobre un empleado
	 * @return void
	 */
	public function run(EmpleadoDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$empleado = Empleado::create();
			if (!is_null($data->id)) {
				$empleado = Empleado::findOne(['id' => $data->id]);
			}
			$empleado->nombre = $data->nombre;
			if (
				$data->hasPassword &&
				!is_null($data->password) &&
				!is_null($data->confirmPassword) &&
				($data->password == $data->confirmPassword)
			) {
				$empleado->pass = password_hash($data->password, PASSWORD_BCRYPT);
			}
			$empleado->color = str_ireplace('#', '', $data->color);
			$empleado->save();

			$this->es->updateRoles($empleado, $data->roles);
		}
	}
}
