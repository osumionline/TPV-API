<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Articulo;

#[OModuleAction(
	url: '/delete-inventario'
)]
class deleteInventarioAction extends OAction {
	/**
	 * Función para borrar un artículo desde el inventario
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$articulo = new Articulo();
			if ($articulo->find(['id' => $id])) {
				$articulo->set('deleted_at', date('Y-m-d H:i:s', time()));
				$articulo->save();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
