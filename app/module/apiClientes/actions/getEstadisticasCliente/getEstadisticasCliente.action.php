<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\UltimasVentasComponent;
use OsumiFramework\App\Component\TopVentasComponent;

#[OModuleAction(
	url: '/get-estadisticas-cliente',
	services: ['clientes'],
	components: ['api/ultimas_ventas', 'api/top_ventas']
)]
class getEstadisticasClienteAction extends OAction {
	/**
	 * Función para obtener las estadísticas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');
		$ultimas_ventas_component = new UltimasVentasComponent(['list' => []]);
		$top_ventas_component = new TopVentasComponent(['list' => []]);

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$ultimas_ventas_component->setValue('list', $this->clientes_service->getUltimasVentas($id));
			$top_ventas_component->setValue('list', $this->clientes_service->getTopVentas($id));
		}

		$this->getTemplate()->add('status',         $status);
		$this->getTemplate()->add('ultimas_ventas', $ultimas_ventas_component);
		$this->getTemplate()->add('top_ventas',     $top_ventas_component);
	}
}