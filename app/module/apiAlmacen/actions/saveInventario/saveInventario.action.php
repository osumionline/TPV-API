<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\InventarioItemDTO;
use OsumiFramework\App\Model\Articulo;

#[OModuleAction(
	url: '/save-inventario',
	services: ['articulos']
)]
class saveInventarioAction extends OAction {
	/**
	 * Función para actualizar un artículo desde el inventario
	 *
	 * @param InventarioItemDTO $data Objeto con información sobre un artículo.
	 * @return void
	 */
	public function run(InventarioItemDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$articulo = new Articulo();
			if ($articulo->find(['id' => $data->getId()])) {
				$articulo->set('stock',  $data->getStock());
				$articulo->set('pvp',    $data->getPvp());
				$articulo->set('margen', $this->articulos_service->getMargen($data->getPuc(), $data->getPvp()));
				$articulo->save();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
