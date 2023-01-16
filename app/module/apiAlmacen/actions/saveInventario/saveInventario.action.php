<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\InventarioItemDTO;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Component\Api\StatusIdMessageComponent;

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
		$id = 'null';
		$message = '';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$articulo = new Articulo();
			if ($articulo->find(['id' => $data->getId()])) {
				$id = $data->getId();

				// Si viene un código de barras compruebo que no esté siendo usado
				if (!is_null($data->getCodigoBarras())) {
					$cb = new CodigoBarras();
					if ($cb->find(['codigo_barras' => $data->getCodigoBarras()])) {
						$cb_articulo = $cb->getArticulo();
						$status = 'error';
						$message = 'El código de barras "'.$data->getCodigoBarras().'" pertenece al artículo "'.$cb_articulo->get('nombre').'".';
					}
				}

				// Si no viene código de barras o si es válido, sigo adelante
				if ($status == 'ok') {
					$articulo->set('stock',  $data->getStock());
					$articulo->set('pvp',    $data->getPvp());
					$articulo->set('margen', $this->articulos_service->getMargen($data->getPuc(), $data->getPvp()));
					$articulo->save();

					if (!is_null($data->getCodigoBarras())) {
						$cb = new CodigoBarras();

						$cb->set('id_articulo', $articulo->get('id'));
						$cb->set('codigo_barras', $data->getCodigoBarras());
						$cb->set('por_defecto', false);
						$cb->save();
					}
				}
			}
			else {
				$status = 'error';
				$message = 'Artículo con id '.$data->getId().' no encontrado.';
			}
		}

		$this->getTemplate()->add('status', new StatusIdMessageComponent(['status' => ['status' => $status, 'id' => $id, 'message' => $message]]));
	}
}
