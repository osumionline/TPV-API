<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class SalidaCajaDTO implements ODTO{
	public ?int    $id          = null;
	public string  $concepto    = '';
	public ?string $descripcion = null;
	public ?float  $importe     = null;

	public function isValid(): bool {
		return (!is_null($this->concepto));
	}

	public function load(ORequest $req): void {
		$this->id          = $req->getParamInt('id');
		$this->concepto    = $req->getParamString('concepto');
		$this->descripcion = $req->getParamString('descripcion');
		$this->importe     = $req->getParamFloat('importe');
	}
}
