<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveTipoPagoOrden;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\TipoPago;

class SaveTipoPagoOrdenComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para actualizar el orden de los tipos de pago
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$list = $req->getParam('list');

		if (is_null($list)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			foreach ($list as $item) {
				$tp = TipoPago::findOne(['id' => $item['id']]);
				if (!is_null($tp)) {
					$tp->orden = $item['orden'];
					$tp->save();
				}
				else {
					$this->status = 'error';
				}
			}
		}
	}
}
