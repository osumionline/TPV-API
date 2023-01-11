<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Factura;
use OsumiFramework\App\Component\Model\FacturaComponent;

#[OModuleAction(
	url: '/get-factura-cliente',
	type: 'json'
)]
class getFacturaClienteAction extends OAction {
	/**
	 * Función para obtener los datos de una factura concreta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$factura_component = new FacturaComponent(['factura' => null]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$factura = new Factura();
			if ($factura->find(['id' => $id])) {
				$factura_component->setValue('factura', $factura);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('factura', $factura_component);
	}
}
