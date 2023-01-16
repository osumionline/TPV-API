<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Component\Api\StatusIdMessageListComponent;

#[OModuleAction(
	url: '/save-all-inventario',
	services: ['articulos']
)]
class saveAllInventarioAction extends OAction {
	/**
	 * Función para guardar masivamente los cambios en el inventario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$list   = $req->getParam('list');
		$result = new StatusIdMessageListComponent(['list' => []]);
		$status_list = [];

		if (is_null($list) || !is_array($list)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			foreach ($list as $item) {
				$item_status = ['status' => 'ok', 'id' => $item['id'], 'message' => ''];
				$articulo = new Articulo();
				if ($articulo->find(['id' => $item['id']])) {
					// Si viene un código de barras compruebo que no esté siendo usado
					if (!is_null($item['codigoBarras'])) {
						$cb = new CodigoBarras();
						if ($cb->find(['codigo_barras' => $item['codigoBarras']])) {
							$item_status['status'] = 'error';
							$cb_articulo = $cb->getArticulo();
							$item_status['message'] = 'El código de barras "'.$item['codigoBarras'].'" pertenece al artículo "'.$cb_articulo->get('nombre').'".';
						}
					}

					// Si no viene código de barras o si es válido, sigo adelante
					if ($item_status['status'] == 'ok') {
						$articulo->set('stock',  $item['stock']);
						$articulo->set('pvp',    $item['pvp']);
						$articulo->set('margen', $this->articulos_service->getMargen($item['puc'], $item['pvp']));
						$articulo->save();

						if (!is_null($item['codigoBarras'])) {
							$cb = new CodigoBarras();
							$cb->set('id_articulo', $articulo->get('id'));
							$cb->set('codigo_barras', $item['codigoBarras']);
							$cb->set('por_defecto', false);
							$cb->save();
						}
					}
				}
				else {
					$item_status['status'] = 'error';
					$item_status['message'] = 'Artículo con id '.$item['id'].' no encontrado.';
				}
				array_push($status_list, $item_status);
			}
			$result->setValue('list', $status_list);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $result);
	}
}
