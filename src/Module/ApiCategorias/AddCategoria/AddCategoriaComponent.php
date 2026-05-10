<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCategorias\AddCategoria;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\CategoriaDTO;
use Osumi\OsumiFramework\App\Model\Categoria;

class AddCategoriaComponent extends OComponent {
  public string       $status = 'ok';
  public string | int $id     = 'null';

	/**
	 * Función para crear una nueva categoría
	 *
	 * @param CategoriaDTO $data Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(CategoriaDTO $data): void {
		if (!$data->isValid()) {
      $this->status = 'error';
    }

    if ($this->status === 'ok') {
      $categoria = Categoria::create();
      $categoria->id_padre = $data->idPadre;
      $categoria->nombre   = urldecode($data->nombre);
      $categoria->save();

      $this->id = $categoria->id;
    }
	}
}
