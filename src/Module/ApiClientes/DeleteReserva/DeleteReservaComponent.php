<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\DeleteReserva;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Reserva;

class DeleteReservaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar una reserva
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
			$reserva = Reserva::findOne(['id' => $id]);
			if (!is_null($reserva)) {
        // Recupero el stock
				$lineas = $reserva->getLineas();
				foreach ($lineas as $linea) {
					$articulo = $linea->getArticulo();
					$articulo->stock = $articulo->stock + $linea->unidades;
					$articulo->save();
				}
        // Borro la reserva
				$reserva->deleteFull();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
