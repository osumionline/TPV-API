<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\InventarioDTO;
use OsumiFramework\App\Component\Api\InventarioItemListComponent;

#[OModuleAction(
	url: '/get-inventario',
	services: ['almacen']
)]
class getInventarioAction extends OAction {
	/**
	 * Función para obtener el listado de elementos para el inventario.
	 *
	 * @param InventarioDTO $data Objeto con la información de los filtros para buscar en el inventario
	 * @return void
	 */
	public function run(InventarioDTO $data):void {
		$status = 'ok';
		$inventario_list_component = new InventarioItemListComponent(['list' => []]);
		$pags = 0;
		$total = 0;

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$inventario = $this->almacen_service->getInventario($data);

			$inventario_list_component->setValue('list', $inventario['list']);
			$pags = $inventario['pags'];
			$total = $inventario['total'];
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $inventario_list_component);
		$this->getTemplate()->add('pags',   $pags);
		$this->getTemplate()->add('total',  $total);
	}
}
