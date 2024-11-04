<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class EmpleadoDTO implements ODTO {
	public ?int    $id               = null;
	public string  $nombre           = '';
	public bool    $has_password     = false;
	public ?string $password         = null;
	public ?string $confirm_password = null;
	public ?string $color            = null;
	public array   $roles            = [];

	public function isValid(): bool {
		return (!is_null($this->nombre));
	}

	public function load(ORequest $req): void {
		$this->id               = $req->getParamInt('id');
		$this->nombre           = $req->getParamString('nombre');
		$this->has_password     = $req->getParamBool('hasPassword');
		$this->password         = $req->getParamString('password');
		$this->confirm_password = $req->getParamString('confirmPassword');
		$this->color            = $req->getParamString('color');
		$this->roles            = $req->getParam('roles');
	}
}
