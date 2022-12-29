<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\HistoricoDTO;
use OsumiFramework\App\Component\Model\VentaListComponent;
use OsumiFramework\App\Model\Venta;

#[OModuleAction(
	url: '/get-historico',
	services: ['ventas']
)]
class getHistoricoAction extends OAction {
	/**
	 * Función para obtener el listado de ventas o detalle de una venta concreta
	 *
	 * @param HistoricoDTO $data Filtros usados para buscar ventas
	 * @return void
	 */
	public function run(HistoricoDTO $data):void {
		$status = 'ok';
		$venta_list_component = new VentaListComponent(['list' => []]);

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			if ($data->getModo() != 'id') {
				$venta_list_component->setValue('list', $this->ventas_service->getHistoricoVentas($data));
			}
			else {
				$venta = new Venta();
				if ($venta->find(['id' => $data->getId()])) {
					$venta_list_component->setValue('list', [$venta]);
				}
				else {
					$status = 'error';
				}
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('list',   $venta_list_component);
	}
}
