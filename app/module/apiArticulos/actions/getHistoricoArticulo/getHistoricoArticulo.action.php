<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\HistoricoArticuloDTO;
use OsumiFramework\App\Component\Model\HistoricoArticuloListComponent;

#[OModuleAction(
	url: '/get-historico-articulo',
	services: ['articulos']
)]
class getHistoricoArticuloAction extends OAction {
	/**
	 * Función para obtener los datos históricos de un artículo
	 *
	 * @param HistoricoArticuloDTO $data Objeto con la información del artículo a buscar
	 * @return void
	 */
	public function run(HistoricoArticuloDTO $data):void {
		$status = 'ok';
		$pags   = 0;
		$historico_articulo_list_component = new HistoricoArticuloListComponent(['list' => []]);

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$historico_articulo_list_component->setValue('list', $this->articulos_service->getHistoricoArticulo($data));
			$pags = $this->articulos_service->getHistoricoArticuloPags($data->getId());
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('pags',   $pags);
		$this->getTemplate()->add('list',   $historico_articulo_list_component);
	}
}
