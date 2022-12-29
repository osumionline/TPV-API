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
		$total_dia = 0;
		$ventas_efectivo = 0;
		$ventas_otros = 0;
		$ventas_web = 0;

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$list = [];
			if ($data->getModo() != 'id') {
				$list = $this->ventas_service->getHistoricoVentas($data);
			}
			else {
				$venta = new Venta();
				if ($venta->find(['id' => $data->getId()])) {
					$list = [$venta];
				}
				else {
					$status = 'error';
				}
			}
			$venta_list_component->setValue('list', $list);
			foreach ($list as $venta) {
				$total_dia += $venta->get('total');
				$ventas_efectivo += $venta->get('entregado');
				if (!is_null($venta->get('id_tipo_pago'))) {
					$tipo_pago = $venta->getTipoPago();
					if ($tipo_pago->get('fisico')) {
						$ventas_otros += $venta->get('total') - $venta->get('entregado');
					}
					else {
						$ventas_web += $venta->get('total') - $venta->get('entregado');
					}
				}
			}
		}

		$this->getTemplate()->add('status',          $status);
		$this->getTemplate()->add('list',            $venta_list_component);
		$this->getTemplate()->add('total_dia',       $total_dia);
		$this->getTemplate()->add('ventas_efectivo', $ventas_efectivo);
		$this->getTemplate()->add('ventas_otros',    $ventas_otros);
		$this->getTemplate()->add('ventas_web',      $ventas_web);
	}
}
