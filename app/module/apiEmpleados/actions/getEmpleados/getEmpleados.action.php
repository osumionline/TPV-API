<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\EmpleadoListComponent;

#[OModuleAction(
	url: '/get-empleados',
	services: ['empleados']
)]
class getEmpleadosAction extends OAction {
	/**
	 * Función para obtener la lista de empleados
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$empleado_list_component = new EmpleadoListComponent(['list' => $this->empleados_service->getEmpleados()]);

		$this->getTemplate()->add('list', $empleado_list_component);
	}
}