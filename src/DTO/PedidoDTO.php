<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class PedidoDTO implements ODTO{
	public ?int    $id             = null;
	public ?int    $id_proveedor   = null;
	public ?int    $id_metodo_pago = null;
	public ?bool   $re             = null;
	public ?bool   $ue             = null;
	public ?string $tipo           = null;
	public ?string $num            = null;
	public ?string $fecha_pago     = null;
	public ?string $fecha_pedido   = null;
	public ?array  $lineas         = null;
	public ?float  $importe        = null;
	public ?float  $portes         = null;
	public ?int    $descuento      = null;
	public ?bool   $faltas         = null;
	public ?bool   $recepcionado   = null;
	public ?string $observaciones  = null;
	public ?array  $pdfs           = null;
	public ?array  $vista          = null;

	public function isValid(): bool {
		return (
			!is_null($this->tipo) &&
			!is_null($this->fecha_pago) &&
			!is_null($this->fecha_pedido) &&
			!is_null($this->lineas) &&
			!is_null($this->recepcionado)
	    );
	}

	public function load(ORequest $req): void {
		$this->id             = $req->getParamInt('id');
		$this->id_proveedor   = $req->getParamInt('idProveedor');
		$this->id_metodo_pago = $req->getParamInt('idMetodoPago');
		$this->re             = $req->getParamBool('re');
		$this->ue             = $req->getParamBool('ue');
		$this->tipo           = $req->getParamString('tipo');
		$this->num            = $req->getParamString('num');
		$this->fecha_pago     = $req->getParamString('fechaPago');
		$this->fecha_pedido   = $req->getParamString('fechaPedido');
		$this->lineas         = $req->getParam('lineas');
		$this->importe        = $req->getParamFloat('importe');
		$this->portes         = $req->getParamFloat('portes');
		$this->descuento      = $req->getParamInt('descuento');
		$this->faltas         = $req->getParamBool('faltas');
		$this->recepcionado   = $req->getParamBool('recepcionado');
		$this->observaciones  = $req->getParamString('observaciones');
		$this->pdfs           = $req->getParam('pdfs');
		$this->vista          = $req->getParam('vista');
	}
}
