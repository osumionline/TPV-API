<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\ReservaListComponent;

#[OModuleAction(
	url: '/get-reservas',
	services: ['clientes']
)]
class getReservasAction extends OAction {
	/**
	 * Función para obtener la lista de reservas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$reserva_list_component = new ReservaListComponent(['list' => $this->clientes_service->getReservas()]);

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $reserva_list_component);
	}
}
