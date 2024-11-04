<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class InstallationDTO implements ODTO{
	public string $nombre           = '';
	public string $nombre_comercial = '';
	public string $cif              = '';
	public string $telefono         = '';
	public string $direccion        = '';
	public string $poblacion        = '';
	public string $email            = '';
	public string $logo             = '';
	public string $nombre_empleado  = '';
	public string $pass             = '';
	public string $color            = '';
	public string $twitter          = '';
	public string $facebook         = '';
	public string $instagram        = '';
	public string $web              = '';
	public float  $caja_inicial     = 0;
	public int    $ticket_inicial   = 1;
	public int    $factura_inicial  = 1;
	public string $tipo_iva         = '';
	public array  $iva_list         = [];
	public array  $re_list          = [];
	public array  $margin_list      = [];
	public bool   $venta_online     = false;
	public string $url_api          = '';
	public string $secret_api       = '';
	public string $backup_api_key   = '';
	public bool   $fecha_cad        = false;
	public bool   $empleados        = false;

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
