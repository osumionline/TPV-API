<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiArticulos\LoadArticulo;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Component\Model\Articulo\ArticuloComponent;

class LoadArticuloComponent extends OComponent {
	public string $status = 'ok';
	public ?ArticuloComponent $articulo = null;

	/**
	 * Función para obtener los datos de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$localizador = $req->getParamInt('localizador');
		$this->articulo = new ArticuloComponent();

		if (is_null($localizador)) {
			$this->status = 'error-1';
		}

		if ($this->status == 'ok') {
			$art = Articulo::findOne(['acceso_directo' => $localizador]);
			if (!is_null($art)) {
				$this->articulo->articulo = $art;
			} else {
				$cb = CodigoBarras::findOne(['codigo_barras' => strval($localizador)]);
				if (!is_null($cb)) {
					$art = Articulo::findOne(['id' => $cb->id_articulo]);
					$this->articulo->articulo = $art;
				} else {
					$this->status = 'error-2';
				}
			}
		}
	}
}
