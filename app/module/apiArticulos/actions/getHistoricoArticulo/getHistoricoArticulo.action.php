<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Component\Model\HistoricoArticuloListComponent;

#[OModuleAction(
	url: '/get-historico-articulo',
	services: ['articulos']
)]
class getHistoricoArticuloAction extends OAction {
	/**
	 * Función para obtener los datos históricos de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status      = 'ok';
		$id_articulo = $req->getParamInt('id');
		$pag         = $req->getParamInt('pag');
		$pags        = 0;
		$historico_articulo_list_component = new HistoricoArticuloListComponent(['list' => []]);

		if (is_null($id_articulo) || is_null($pag)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$historico_articulo_list_component->setValue('list', $this->articulos_service->getHistoricoArticulo($id_articulo, $pag));
			$pags = $this->articulos_service->getHistoricoArticuloPags($id_articulo);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('pags',   $pags);
		$this->getTemplate()->add('list',   $historico_articulo_list_component);
	}
}
