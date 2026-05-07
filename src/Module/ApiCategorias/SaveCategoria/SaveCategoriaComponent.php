<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCategorias\SaveCategoria;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\CategoriaDTO;
use Osumi\OsumiFramework\App\Model\Categoria;

class SaveCategoriaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para actualizar una categoría
	 *
	 * @param CategoriaDTO $data Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(CategoriaDTO $data): void {
		if (!$data->isValid()) {
      $this->status = 'error';
    }

    if ($this->status === 'ok') {
      $categoria = Categoria::findOne(['id' => $data->id]);
      if (!is_null($categoria)) {
        $categoria->id_padre = $data->idPadre;
        $categoria->nombre   = urldecode($data->nombre);
        $categoria->save();
      }
      else {
        $this->status = 'error';
      }
    }
	}
}
