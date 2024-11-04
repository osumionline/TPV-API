<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiArticulos\AsignarAccesoDirecto;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Articulo;

class AsignarAccesoDirectoComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para asignar un acceso directo a un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');
		$acceso_directo = $req->getParamInt('accesoDirecto');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$art = Articulo::findOne(['id' => $id]);
			if (!is_null($art)) {
				$art->acceso_directo = $acceso_directo;
				$art->save();
			}
      else {
        $this->status = 'error';
      }
		}
	}
}
