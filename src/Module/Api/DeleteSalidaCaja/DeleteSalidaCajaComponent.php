<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\DeleteSalidaCaja;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\PagoCaja;

class DeleteSalidaCajaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar una salida de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$pc = PagoCaja::findOne(['id' => $id]);
			if (!is_null($pc)) {
				$pc->delete();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
