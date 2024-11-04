<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class InventarioItemDTO implements ODTO {
	public ?int    $id            = null;
	public ?int    $localizador   = null;
	public ?string $marca         = null;
	public ?string $referencia    = null;
	public ?string $nombre        = null;
	public ?int    $stock         = null;
	public ?float  $puc           = null;
	public ?float  $pvp           = null;
	public ?string $codigo_barras = null;

	public function isValid(): bool {
		return (
			!is_null($this->id) &&
			!is_null($this->stock) &&
			!is_null($this->pvp)
		);
	}

	public function load(ORequest $req): void {
		$this->id            = $req->getParamInt('id');
		$this->localizador   = $req->getParamInt('localizador');
		$this->marca         = $req->getParamString('marca');
		$this->referencia    = $req->getParamString('referencia');
		$this->nombre        = $req->getParamString('nombre');
		$this->stock         = $req->getParamInt('stock');
		$this->puc           = $req->getParamFloat('puc');
		$this->pvp           = $req->getParamFloat('pvp');
		$this->codigo_barras = $req->getParamString('codigoBarras');
	}
}
