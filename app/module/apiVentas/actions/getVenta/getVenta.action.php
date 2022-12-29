<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Api\VentaFinComponent;

#[OModuleAction(
	url: '/get-venta',
	services: ['ticket']
)]
class getVentaAction extends OAction {
	/**
	 * Función para obtener el detalle de una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$venta_fin_component = new VentaFinComponent(['datos' => []]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status ==  'ok') {
			$venta_fin_component->setValue('datos', $this->ticket_service->getVenta($id));
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('venta', $venta_fin_component);
	}
}
