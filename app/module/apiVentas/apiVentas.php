<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\DTO\VentaDTO;

#[ORoute(
	type: 'json',
	prefix: '/api-ventas'
)]
class apiVentas extends OModule {
	/**
	 * Función para guardar una venta
	 *
	 * @param VentaDTO Datos de la venta
	 * @return void
	 */
	#[ORoute('/save-venta')]
	public function saveVenta(VentaDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$venta = new Venta();
			$venta->set('id_cliente',   ($data->getIdCliente() != -1) ? $data->getIdCliente() : null);
			$venta->set('total',        $data->getTotal());
			$venta->set('entregado',    $data->getEfectivo());
			$venta->set('pago_mixto',   $data->getPagoMixto());
			$venta->set('id_tipo_pago', $data->getIdTipoPago());
			$venta->set('saldo', null);
			$venta->save();

			foreach ($data->getLineas() as $linea) {
				$lv = new LineaVenta();
				$art = new Articulo();
				$art->find(['id' => $linea['idArticulo']]);

				$lv->set('id_venta', $venta->get('id'));
				$lv->set('id_articulo', $linea['idArticulo']);
				$lv->set('puc', $art->get('puc'));
				$lv->set('pvp', $art->get('pvp'));
				$lv->set('iva', $art->get('iva'));
				$lv->set('re', $art->get('re'));
				$lv->set('importe', $linea['importe']);
				if (!$linea['descuentoManual']) {
					$lv->set('descuento', $linea['descuento']);
					$lv->set('importe_descuento', null);
				}
				else {
					$lv->set('descuento', null);
					$lv->set('importe_descuento', $linea['descuento']);
				}
				$lv->set('devuelto', 0);
				$lv->set('unidades', $linea['cantidad']);
				$lv->save();
			}
		}

		$this->getTemplate()->add('status', $status);
	}
}
