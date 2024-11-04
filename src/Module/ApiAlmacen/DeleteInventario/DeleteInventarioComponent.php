<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiAlmacen\DeleteInventario;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Articulo;

class DeleteInventarioComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar un artículo desde el inventario
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
			$articulo = Articulo::findOne(['id' => $id]);
			if (!is_null($articulo)) {
				$articulo->deleted_at = date('Y-m-d H:i:s', time());
				$articulo->save();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
