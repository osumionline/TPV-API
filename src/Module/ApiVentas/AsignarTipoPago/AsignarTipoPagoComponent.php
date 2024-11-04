<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\AsignarTipoPago;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Venta;

class AsignarTipoPagoComponent extends OComponent {
	public string $status = 'ok';

	/**
	 * Función para asignar un tipo de pago a una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id           = $req->getParamInt('id');
		$id_tipo_pago = $req->getParamInt('idTipoPago');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$venta = Venta::findOne(['id' => $id]);
			if (!is_null($venta)) {
				if (is_null($venta->id_tipo_pago) && $id_tipo_pago !== -1) {
					$venta->id_tipo_pago = $id_tipo_pago;
					$venta->entregado    = 0;
					$venta->save();
				} else {
					$venta->id_tipo_pago = null;
					$venta->entregado    = $venta->total;
					$venta->save();
				}
			} else {
				$this->status = 'error';
			}
		}
	}
}
