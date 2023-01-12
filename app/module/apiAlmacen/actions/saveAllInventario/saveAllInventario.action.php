<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Articulo;

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
		$list = $req->getParam('list');

		if (is_null($list) || !is_array($list)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			foreach ($list as $item) {
				$articulo = new Articulo();
				if ($articulo->find(['id' => $item['id']])) {
					$articulo->set('stock',  $item['stock']);
					$articulo->set('pvp',    $item['pvp']);
					$articulo->set('margen', $this->articulos_service->getMargen($item['puc'], $item['pvp']));
					$articulo->save();
				}
				else {
					$status = 'error';
				}
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
