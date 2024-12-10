<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\DeleteLineaReserva;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\LineaReserva;

class DeleteLineaReservaComponent extends OComponent {
	public string $status = 'ok';

	/**
   * Función para borrar una línea de una reserva
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
			$linea = LineaReserva::findOne(['id' => $id]);
			if (!is_null($linea)) {
				$articulo = $linea->getArticulo();
				$articulo->stock = $articulo->stock + $linea->unidades;
				$articulo->save();

				$linea->delete();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
