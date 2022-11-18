<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\PagocajaListComponent;

#[OModuleAction(
	url: '/get-salidas-caja',
	services: ['general']
)]
class getSalidasCajaAction extends OAction {
	/**
	 * Función para obtener las salidas de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$modo   = $req->getParamString('modo');
		$fecha  = $req->getParamString('fecha');
		$desde  = $req->getParamString('desde');
		$hasta  = $req->getParamString('hasta');
		$pago_caja_list_component = new PagocajaListComponent(['list' => []]);

		if (is_null($fecha) && is_null($desde) && is_null($hasta)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list = $this->general_service->getSalidasCaja($modo, $fecha, $desde, $hasta);
			$pago_caja_list_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $pago_caja_list_component);
	}
}
