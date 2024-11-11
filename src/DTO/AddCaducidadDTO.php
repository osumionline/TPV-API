<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class AddCaducidadDTO implements ODTO {
	public ?int $id_articulo = null;
	public ?int $unidades    = null;

	public function isValid(): bool {
		return (!is_null($this->id_articulo) && !is_null($this->unidades));
	}

	public function load(ORequest $req): void {
		$this->id_articulo = $req->getParamInt('idArticulo');
		$this->unidades    = $req->getParamInt('unidades');
	}
}
