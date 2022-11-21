<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidosFilterDTO;
use OsumiFramework\App\Component\Model\PedidoListComponent;

#[OModuleAction(
	url: '/get-pedidos-recepcionados',
	services: ['compras']
)]
class getPedidosRecepcionadosAction extends OAction {
	/**
	 * Función para obtener el listado de pedidos recepcionados
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 * @return void
	 */
	public function run(PedidosFilterDTO $data):void {
		$status = 'ok';
		$pedidos_recepcionados_component = new PedidoListComponent(['list' => []]);
		$pedidos_recepcionados_pags      = 0;

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$pedidos = $this->compras_service->getPedidosRecepcionados($data);

			$pedidos_recepcionados_component->setValue('list', $pedidos['list']);
			$pedidos_recepcionados_pags = $pedidos['pags'];
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $pedidos_recepcionados_component);
		$this->getTemplate()->add('pags',   $pedidos_recepcionados_pags);
	}
}
