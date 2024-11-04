<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class ComercialDTO implements ODTO {
	public ?int    $id            = null;
	public ?int    $id_proveedor  = null;
	public ?string $nombre        = '';
	public ?string $telefono      = '';
	public ?string $email         = '';
	public ?string $observaciones = '';

	public function isValid(): bool {
		return (!is_null($this->nombre));
	}

	public function load(ORequest $req): void {
		$this->id            = $req->getParamInt('id');
		$this->id_proveedor  = $req->getParamInt('idProveedor');
		$this->nombre        = $req->getParamString('nombre');
		$this->telefono      = $req->getParamString('telefono');
		$this->email         = $req->getParamString('email');
		$this->observaciones = $req->getParamString('observaciones');
	}
}
