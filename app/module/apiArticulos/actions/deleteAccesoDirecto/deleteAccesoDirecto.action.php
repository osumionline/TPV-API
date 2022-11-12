<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Articulo;

#[OModuleAction(
	url: '/delete-acceso-directo'
)]
class deleteAccesoDirectoAction extends OAction {
	/**
	 * Función para borrar un acceso directo de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if ($art->find(['id' => $id])) {
				$art->set('acceso_directo', null);
				$art->save();
			}
		}
		
		$this->getTemplate()->add('status', $status);
	}
}