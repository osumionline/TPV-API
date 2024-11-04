<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class ProveedorDTO implements ODTO{
	public ?int    $id            = null;
	public string  $nombre        = '';
	public ?int    $id_foto       = null;
	public ?string $foto          = null;
	public string  $direccion     = '';
	public string  $telefono      = '';
	public string  $email         = '';
	public string  $web           = '';
	public string  $observaciones = '';
	public array   $marcas        = [];

	public function isValid(): bool {
		return (!is_null($this->nombre));
	}

	public function load(ORequest $req): void {
		$this->id            = $req->getParamInt('id');
		$this->nombre        = $req->getParamString('nombre');
		$this->id_foto       = $req->getParamInt('idFoto');
		$this->foto          = $req->getParamString('foto');
		$this->direccion     = $req->getParamString('direccion');
		$this->telefono      = $req->getParamString('telefono');
		$this->email         = $req->getParamString('email');
		$this->web           = $req->getParamString('web');
		$this->observaciones = $req->getParamString('observaciones');
		$this->marcas        = $req->getParam('marcas');
	}
}
