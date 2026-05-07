<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCategorias\SaveArticulosCategoria;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\CategoriaArticulosDTO;
use Osumi\OsumiFramework\App\Model\Categoria;

class SaveArticulosCategoriaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para actualizar la lista de artículos de una categoría
	 *
	 * @param CategoriaArticulosDTO $data Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(CategoriaArticulosDTO $data): void {
		if (!$data->isValid()) {
      $this->status = 'error';
    }

    if ($this->status === 'ok') {
      $categoria = Categoria::findOne(['id' => $data->idCategoria]);
      if (!is_null($categoria)) {
        $categoria->updateArticulos($data->idArticulos);
      }
      else {
        $this->status = 'error';
      }
    }
	}
}
