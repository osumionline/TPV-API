<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class PedidosFilterDTO implements ODTO {
	public ?string $fecha_desde   = null;
	public ?string $fecha_hasta   = null;
	public ?int    $id_proveedor  = null;
	public ?string $albaran       = null;
	public ?float  $importe_desde = null;
	public ?float  $importe_hasta = null;
	public ?int    $pagina        = null;
	public ?int    $num           = null;

	public function isValid(): bool {
		return (
			!is_null($this->pagina) &&
			!is_null($this->num)
		);
	}

	public function load(ORequest $req): void {
		$this->fecha_desde   = $req->getParamString('fechaDesde');
		$this->fecha_hasta   = $req->getParamString('fechaHasta');
		$this->id_proveedor  = $req->getParamInt('idProveedor');
		$this->albaran       = $req->getParamString('albaran');
		$this->importe_desde = $req->getParamFloat('importeDesde');
		$this->importe_hasta = $req->getParamFloat('importeHasta');
		$this->pagina        = $req->getParamInt('pagina');
		$this->num           = $req->getParamInt('num');
	}
}
