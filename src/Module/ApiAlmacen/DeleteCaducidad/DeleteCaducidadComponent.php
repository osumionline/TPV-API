<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\DeleteCaducidad;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Caducidad;

class DeleteCaducidadComponent extends OComponent {
	public string $status = 'ok';

	/**
   * Función para borrar una caducidad
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
			$caducidad = Caducidad::findOne(['id' => $id]);
			if (!is_null($caducidad)) {
				$caducidad->delete();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
