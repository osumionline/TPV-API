<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\GetLocalizadores;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Component\Model\ArticuloList\ArticuloListComponent;

class GetLocalizadoresComponent extends OComponent {
	public string $status = 'ok';
	public ?ArticuloListComponent $list = null;

	/**
	 * Función para obtener el detalle de unas líneas de ticket concretas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$localizadores = $req->getParamString('localizadores');
		$this->list = new ArticuloListComponent();

		if (is_null($localizadores)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$localizadores_list = explode(',', $localizadores);
			$list = [];

			foreach ($localizadores_list as $localizador) {
        $cb = CodigoBarras::findOne(['codigo_barras' => strval($localizador)]);
				if (!is_null($cb)) {
					$art = Articulo::findOne(['id' => $cb->id_articulo]);
					$list[] = $art;
				} else {
					$this->status = 'error';
          break;
				}
			}

			$this->list->list = $list;
		}
	}
}
