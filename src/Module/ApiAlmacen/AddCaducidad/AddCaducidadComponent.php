<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\AddCaducidad;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\AddCaducidadDTO;
use Osumi\OsumiFramework\App\Model\Caducidad;
use Osumi\OsumiFramework\App\Model\Articulo;

class AddCaducidadComponent extends OComponent {
	public string $status = 'ok';

	/**
   * Función para añadir una nueva caducidad
	 *
	 * @param AddCaducidadDTO $data Datos de la nueva caducidad
	 * @return void
	 */
	public function run(AddCaducidadDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$articulo = Articulo::findOne(['id' => $data->idArticulo]);
			if (!is_null($articulo)) {
				$caducidad = Caducidad::create();
				$caducidad->id_articulo = $articulo->id;
				$caducidad->unidades    = $data->unidades;
				$caducidad->puc         = $articulo->puc;
				$caducidad->pvp         = $articulo->pvp;
				$caducidad->save();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
