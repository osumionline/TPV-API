<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class TipoPagoDTO implements ODTO{
	public ?int    $id          = null;
	public string  $nombre      = '';
	public ?string $foto        = null;
	public ?bool   $afecta_caja = null;
	public ?int    $orden       = null;
	public ?bool   $fisico      = null;

	public function isValid(): bool {
		return (!is_null($this->nombre));
	}

	public function load(ORequest $req): void {
		$this->id          = $req->getParamInt('id');
		$this->nombre      = $req->getParamString('nombre');
		$this->foto        = $req->getParamString('foto');
		$this->afecta_caja = $req->getParamBool('afectaCaja', false);
		$this->orden       = $req->getParamInt('orden');
		$this->fisico      = $req->getParamBool('fisico', true);
	}
}
