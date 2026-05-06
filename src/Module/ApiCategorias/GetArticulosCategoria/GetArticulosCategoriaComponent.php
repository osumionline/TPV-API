<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCategorias\GetArticulosCategoria;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Component\Model\ArticuloList\ArticuloListComponent;
use Osumi\OsumiFramework\App\Model\Categoria;

class GetArticulosCategoriaComponent extends OComponent {
  public string                 $status = 'ok';
  public ?ArticuloListComponent $list   = null;

	/**
	 * Función para obtener la lista de artículos de una categoría
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');
    $this->list = new ArticuloListComponent();

    if (is_null($id)) {
      $this->status = 'error';
    }

    if ($this->status === 'ok') {
      $categoria = Categoria::findOne(['id' => $id]);
      if (!is_null($categoria)) {
        $this->list->list = $categoria->getArticulos();
      }
      else {
        $this->status = 'error';
      }
    }
	}
}
