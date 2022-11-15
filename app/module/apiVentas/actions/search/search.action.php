<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Api\BuscadorVentasListComponent;

#[OModuleAction(
	url: '/search',
	services: ['articulos']
)]
class searchAction extends OAction {
	/**
	 * Buscador de artículos para venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$q = $req->getParamString('q');
		$buscador_ventas_list_component = new BuscadorVentasListComponent(['list' => []]);

		if (is_null($q)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$buscador_ventas_list_component->setValue('list', $this->articulos_service->searchArticulosVentas($q));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $buscador_ventas_list_component);
	}
}
