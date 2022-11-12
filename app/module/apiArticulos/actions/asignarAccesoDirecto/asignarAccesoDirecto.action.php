<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Articulo;

#[OModuleAction(
	url: '/asignar-acceso-directo'
)]
class asignarAccesoDirectoAction extends OAction {
	/**
	 * Función para asignar un acceso directo a un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$acceso_directo = $req->getParamInt('accesoDirecto');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if ($art->find(['id' => $id])) {
				$art->set('acceso_directo', $acceso_directo);
				$art->save();
			}
		}
		
		$this->getTemplate()->add('status', $status);
	}
}