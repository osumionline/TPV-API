<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\ArticuloListComponent;

#[OModuleAction(
	url: '/search-articulos',
	services: ['articulos'],
	components: ['model/articulo_list']
)]
class searchArticulosAction extends OAction {
	/**
	 * Función para buscar artículos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$name = $req->getParamString('name');
		$id_marca = $req->getParamInt('idMarca');
		$articulo_list_component = new ArticuloListComponent(['list' => []]);

		if (is_null($name) || is_null($id_marca)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$articulo_list_component->setValue('list', $this->articulos_service->searchArticulos($name, $id_marca));
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $articulo_list_component);
	}
}
