<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\VentaListComponent;

#[OModuleAction(
	url: '/get-historico',
	services: ['ventas']
)]
class getHistoricoAction extends OAction {
	/**
	 * Función para obtener el listado de ventas
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
		$venta_list_component = new VentaListComponent(['list' => []]);

		if (is_null($fecha) && is_null($rango_desde) && is_null($rango_hasta)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list = $this->ventas_service->getHistoricoVentas($modo, $fecha, $desde, $hasta);
			$venta_list_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('list',    $venta_list_component);
	}
}
