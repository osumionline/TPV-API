<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\TipoPagoListComponent;

#[OModuleAction(
	url: '/get-tipos-pago',
	services: ['general']
)]
class getTiposPagoAction extends OAction {
	/**
	 * Función para obtener la lista de tipos de pago
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$tipos_pago_component = new TipoPagoListComponent(['list' => $this->general_service->getTiposPago()]);
		$this->getTemplate()->add('list', $tipos_pago_component);
	}
}
