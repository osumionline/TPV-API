<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiArticulos\DeleteArticulo;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Articulo;

class DeleteArticuloComponent extends OComponent {
	public string $status = 'ok';

	/**
	 * Función para dar de baja un artículo
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
			$art = Articulo::findOne(['id' => $id]);
			if (!is_null($art)) {
				$art->deleted_at = date('Y-m-d H:i:s', time());
				$art->save();
			} else {
				$this->status = 'error';
			}
		}
	}
}
