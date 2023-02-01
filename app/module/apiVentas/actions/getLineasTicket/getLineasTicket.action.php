<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Component\Model\LineaVentaListComponent;

#[OModuleAction(
	url: '/get-lineas-ticket'
)]
class getLineasTicketAction extends OAction {
	/**
	 * Función para obtener el detalle de unas líneas de ticket concretas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$lineas = $req->getParamString('lineas');
		$linea_venta_list_component = new LineaVentaListComponent(['list' => []]);

		if (is_null($lineas)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$lineas_list = explode(',', $lineas);
			$list = [];

			foreach ($lineas_list as $linea) {
				$lv = new LineaVenta();
				$lv->find(['id' => $linea]);
				array_push($list, $lv);
			}

			$linea_venta_list_component->setValue('list', $list);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $linea_venta_list_component);
	}
}
