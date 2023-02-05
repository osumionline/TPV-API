<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;

#[OModuleAction(
	url: '/stock',
	services: ['sync']
)]
class syncStockAction extends OAction {
	/**
	 * Función para sincronizar el stock del TPV con la web
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		echo $this->sync_service->syncStock();
		exit;
	}
}
