<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\EmpleadoDTO;
use OsumiFramework\App\Model\Empleado;

#[OModuleAction(
	url: '/save-empleado',
	services: ['empleados']
)]
class saveEmpleadoAction extends OAction {
	/**
	 * Función para guardar los datos de un empleado
	 *
	 * @param EmpleadoDTO $data Objeto con toda la información sobre un empleado
	 * @return void
	 */
	public function run(EmpleadoDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$empleado = new Empleado();
			if (!is_null($data->getId())) {
				$empleado->find(['id' => $data->getId()]);
			}
			$empleado->set('nombre', $data->getNombre());
			if (
				$data->getHasPassword() &&
				!is_null($data->getPassword()) &&
				!is_null($data->getConfirmPassword()) &&
				($data->getPassword() == $data->getConfirmPassword())
			) {
				$empleado->set('pass', password_hash($data->getPassword(), PASSWORD_BCRYPT));
			}
			$empleado->set('color', str_ireplace('#', '', $data->getColor()));
			$empleado->save();

			$this->empleados_service->updateRoles($empleado, $data->getRoles());
		}

		$this->getTemplate()->add('status', $status);
	}
}
