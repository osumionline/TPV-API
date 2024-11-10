<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class ClienteDTO implements ODTO {
	public ?int    $id                    = null;
	public string $nombre_apellidos      = '';
	public ?string $dni_cif               = '';
	public ?string $telefono              = null;
	public ?string $email                 = null;
	public ?string $direccion             = null;
	public ?string $codigo_postal         = null;
	public ?string $poblacion             = null;
	public ?int    $provincia             = null;
	public bool    $fact_igual            = true;
	public ?string $fact_nombre_apellidos = null;
	public ?string $fact_dni_cif          = null;
	public ?string $fact_telefono         = null;
	public ?string $fact_email            = null;
	public ?string $fact_direccion        = null;
	public ?string $fact_codigo_postal    = null;
	public ?string $fact_poblacion        = null;
	public ?int    $fact_provincia        = null;
	public ?string $observaciones         = null;
	public ?int    $descuento             = null;

	public function isValid(): bool {
		return (!is_null($this->nombre_apellidos));
	}

	public function load(ORequest $req): void {
		$this->id                    = $req->getParamInt('id');
		$this->nombre_apellidos      = $req->getParamString('nombreApellidos');
		$this->dni_cif               = $req->getParamString('dniCif');
		$this->telefono              = $req->getParamString('telefono');
		$this->email                 = $req->getParamString('email');
		$this->direccion             = $req->getParamString('direccion');
		$this->codigo_postal         = $req->getParamString('codigoPostal');
		$this->poblacion             = $req->getParamString('poblacion');
		$this->provincia             = $req->getParamInt('provincia');
		$this->fact_igual            = $req->getParamBool('factIgual');
		$this->fact_nombre_apellidos = $req->getParamString('factNombreApellidos');
		$this->fact_dni_cif          = $req->getParamString('factDniCif');
		$this->fact_telefono         = $req->getParamString('factTelefono');
		$this->fact_email            = $req->getParamString('factEmail');
		$this->fact_direccion        = $req->getParamString('factDireccion');
		$this->fact_codigo_postal    = $req->getParamString('factCodigoPostal');
		$this->fact_poblacion        = $req->getParamString('factPoblacion');
		$this->fact_provincia        = $req->getParamInt('factProvincia');
		$this->observaciones         = $req->getParamString('observaciones');
		$this->descuento             = $req->getParamInt('descuento');
	}
}
