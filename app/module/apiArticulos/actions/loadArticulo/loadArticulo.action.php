<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Component\Model\ArticuloComponent;

#[OModuleAction(
	url: '/load-articulo'
)]
class loadArticuloAction extends OAction {
	/**
	 * Función para obtener los datos de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$localizador = $req->getParamInt('localizador');
		$articulo_component = new ArticuloComponent(['articulo' => null]);

		if (is_null($localizador)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$articulo = new Articulo();
			if ($articulo->find(['acceso_directo' => $localizador])) {
				$articulo_component->setValue('articulo', $articulo);
			}
			else {
				$cb = new CodigoBarras();
				if ($cb->find(['codigo_barras' => strval($localizador)])) {
					$articulo->find(['id' => $cb->get('id_articulo')]);
					$articulo_component->setValue('articulo', $articulo);
				}
				else {
					$status = 'error';
				}
			}
		}

		$this->getTemplate()->add('status',   $status);
		$this->getTemplate()->add('articulo', $articulo_component);
	}
}
