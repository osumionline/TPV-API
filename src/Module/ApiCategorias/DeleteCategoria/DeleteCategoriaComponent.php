<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCategorias\DeleteCategoria;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Categoria;

class DeleteCategoriaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar una categoría
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
      $categoria = Categoria::findOne(['id' => $id]);
      if (!is_null($categoria)) {
        $categoria->deleteFull();
      }
      else {
        $this->status = 'error';
      }
    }
	}
}
