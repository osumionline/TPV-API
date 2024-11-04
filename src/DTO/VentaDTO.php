<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class VentaDTO implements ODTO{
	public ?float  $efectivo     = null;
	public ?float  $cambio       = null;
	public ?float  $tarjeta      = null;
	public ?int    $id_empleado  = null;
	public ?int    $id_tipo_pago = null;
	public ?int    $id_cliente   = null;
	public ?float  $total        = null;
	public array   $lineas       = [];
	public bool    $pago_mixto   = false;
	public ?string $imprimir     = null;
	public ?string $email        = null;

	public function isValid(): bool {
		return (
			count($this->lineas) > 0 &&
			!is_null($this->imprimir)
		);
	}

	public function load(ORequest $req): void {
		$this->efectivo     = $req->getParamFloat('efectivo');
		$this->cambio       = $req->getParamFloat('cambio');
		$this->tarjeta      = $req->getParamFloat('tarjeta');
		$this->id_empleado  = $req->getParamInt('idEmpleado');
		$this->id_tipo_pago = $req->getParamInt('idTipoPago');
		$this->id_cliente   = $req->getParamInt('idCliente');
		$this->total        = $req->getParamFloat('total');
		$this->lineas       = $req->getParam('lineas');
		$this->pago_mixto   = $req->getParamBool('pagoMixto');
		$this->imprimir     = $req->getParamString('imprimir');
		$this->email        = $req->getParamString('email');
	}
}
