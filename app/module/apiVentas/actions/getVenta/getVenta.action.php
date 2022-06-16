<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\FacturaComponent;

#[OModuleAction(
	url: '/get-venta',
	services: ['ticket'],
	components: ['api/factura']
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
		$factura_component = new FacturaComponent(['datos' => []]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status ==  'ok') {
			$factura_component->setValue('datos', $this->ticket_service->getVenta($id));
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('factura', $factura_component);
	}
}