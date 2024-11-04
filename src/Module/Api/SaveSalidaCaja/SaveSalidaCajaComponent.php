<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\SaveSalidaCaja;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\SalidaCajaDTO;
use Osumi\OsumiFramework\App\Model\PagoCaja;

class SaveSalidaCajaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para guardar una salida de caja
	 *
	 * @param SalidaCajaDTO $data Objeto con los datos de la salida de caja
	 * @return void
	 */
	public function run(SalidaCajaDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$pc = PagoCaja::create();
			if (!is_null($data->id)) {
				$pc = PagoCaja::findOne(['id' => $data->id]);
			}
			$pc->concepto    = urldecode($data->concepto);
			$pc->descripcion = !is_null($data->descripcion) ? urldecode($data->descripcion) : null;
			$pc->importe     = $data->importe;

			$pc->save();
		}
	}
}
