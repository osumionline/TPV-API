<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class InventarioDTO implements ODTO {
	public ?int    $id_proveedor = null;
	public ?int    $id_marca     = null;
	public ?string $nombre       = null;
	public ?bool   $descuento    = null;
	public ?string $order_by     = null;
	public ?string $order_sent   = null;
	public ?int    $pagina       = null;
	public ?int    $num          = null;

	public function isValid(): bool {
		return (!is_null($this->pagina));
	}

	public function load(ORequest $req): void {
		$this->id_proveedor = $req->getParamInt('idProveedor');
		$this->id_marca     = $req->getParamInt('idMarca');
		$this->nombre       = $req->getParamString('nombre');
		$this->descuento    = $req->getParamBool('descuento');
		$this->order_by     = $req->getParamString('orderBy');
		$this->order_sent   = $req->getParamString('orderSent');
		$this->pagina       = $req->getParamInt('pagina');
		$this->num          = $req->getParamInt('num');
	}
}
