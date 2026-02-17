<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class InstallationDTO extends ODTO{
	#[ODTOField(required: true)]
	public string $nombre = '';

	#[ODTOField(required: true)]
	public string $nombreComercial = '';

	#[ODTOField(required: true)]
	public string $cif = '';

	#[ODTOField(required: false)]
	public string $telefono = '';

	#[ODTOField(required: false)]
	public string $direccion = '';

	#[ODTOField(required: false)]
	public string $poblacion = '';

	#[ODTOField(required: false)]
	public string $email = '';

	#[ODTOField(required: true)]
	public string $logo = '';

	#[ODTOField(required: false)]
	public string $nombreEmpleado = '';

	#[ODTOField(required: false)]
	public string $pass = '';

	#[ODTOField(required: true)]
	public string $color = '';

	#[ODTOField(required: false)]
	public string $twitter = '';

	#[ODTOField(required: false)]
	public string $facebook = '';

	#[ODTOField(required: false)]
	public string $instagram = '';

	#[ODTOField(required: false)]
	public string $web = '';

	#[ODTOField(required: false)]
	public float $cajaInicial = 0;

	#[ODTOField(required: false)]
	public int $ticketInicial = 1;

	#[ODTOField(required: false)]
	public int $facturaInicial = 1;

	#[ODTOField(required: true)]
	public string $tipoIva = '';

	#[ODTOField(required: false)]
	public array $ivaList = [];

	#[ODTOField(required: false)]
	public array $reList = [];

	#[ODTOField(required: false)]
	public array $marginList = [];

	#[ODTOField(required: false)]
	public bool $ventaOnline = false;

	#[ODTOField(required: false)]
	public string $urlApi = '';

	#[ODTOField(required: false)]
	public string $secretApi = '';

	#[ODTOField(required: false)]
	public string $backupApiKey = '';

	#[ODTOField(required: false)]
	public bool $fechaCad = false;

	#[ODTOField(required: false)]
	public bool $empleados = false;

	public function isValid(): bool {
		return (
			$this->nombre !== '' &&
			$this->nombre_comercial !== '' &&
			$this->cif !== '' &&
			$this->logo !== '' &&
			$this->color !== '' &&
			$this->tipo_iva !== '' &&
			count($this->iva_list) > 0 &&
			(
				!$this->venta_online ||
				(
					$this->venta_online &&
					$this->url_api !== '' &&
					$this->secret_api !== ''
				)
			)
		);
	}

	public function load(ORequest $req): void {
		$this->nombre           = $req->getParamString('nombre');
		$this->nombre_comercial = $req->getParamString('nombreComercial');
		$this->cif              = $req->getParamString('cif');
		$this->telefono         = $req->getParamString('telefono');
		$this->direccion        = $req->getParamString('direccion');
		$this->poblacion        = $req->getParamString('poblacion');
		$this->email            = $req->getParamString('email');
		$this->logo             = $req->getParamString('logo');
		$this->nombre_empleado  = $req->getParamString('nombreEmpleado');
		$this->pass             = $req->getParamString('pass');
		$this->color            = $req->getParamString('color');
		$this->twitter          = $req->getParamString('twitter');
		$this->facebook         = $req->getParamString('facebook');
		$this->instagram        = $req->getParamString('instagram');
		$this->web              = $req->getParamString('web');
		$this->caja_inicial     = $req->getParamFloat('cajaInicial');
		$this->ticket_inicial   = $req->getParamInt('ticketInicial');
		$this->factura_inicial  = $req->getParamInt('facturaInicial');
		$this->tipo_iva         = $req->getParamString('tipoIva');
		$this->iva_list         = $req->getParam('ivaList');
		$this->re_list          = $req->getParam('reList');
		$this->margin_list      = $req->getParam('marginList');
		$this->venta_online     = $req->getParamBool('ventaOnline', false);
		$this->url_api          = $req->getParamString('urlApi');
		$this->secret_api       = $req->getParamString('secretApi');
		$this->backup_api_key   = $req->getParamString('backupApiKey');
		$this->fecha_cad        = $req->getParamBool('fechaCad', false);
		$this->empleados        = $req->getParamBool('empleados', false);
	}
}
