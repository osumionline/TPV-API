<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidosFilterDTO;
use OsumiFramework\App\Component\Model\PedidoListComponent;

#[OModuleAction(
	url: '/get-pedidos',
	services: ['compras']
)]
class getPedidosAction extends OAction {
	/**
	 * Función para obtener el listado de pedidos
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 * @return void
	 */
	public function run(PedidosFilterDTO $data):void {
		$status = 'ok';
		$pedidos_guardados_component     = new PedidoListComponent(['list' => []]);
		$pedidos_recepcionados_component = new PedidoListComponent(['list' => []]);
		$pedidos_guardados_pags     = 0;
		$pedidos_recepcionados_pags = 0;

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$pedidos = $this->compras_service->getPedidos($data);

			$pedidos_guardados_component->setValue('list',     $pedidos['guardados']);
			$pedidos_recepcionados_component->setValue('list', $pedidos['recepcionados']);
			$pedidos_guardados_pags     = $pedidos['guardados_pags'];
			$pedidos_recepcionados_pags = $pedidos['recepcionados_pags'];
		}

		$this->getTemplate()->add('status',             $status);
		$this->getTemplate()->add('guardados',          $pedidos_guardados_component);
		$this->getTemplate()->add('recepcionados',      $pedidos_recepcionados_component);
		$this->getTemplate()->add('guardados_pags',     $pedidos_guardados_pags);
		$this->getTemplate()->add('recepcionados_pags', $pedidos_recepcionados_pags);
	}
}
