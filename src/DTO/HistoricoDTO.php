<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class HistoricoDTO implements ODTO {
	public ?int    $id    = null;
	public ?string $modo  = null;
	public ?string $fecha = null;
	public ?string $desde = null;
	public ?string $hasta = null;

	public function isValid(): bool {
		return (
			!is_null($this->modo) &&
			(
				($this->modo === 'id'    && !is_null($this->id)) ||
				($this->modo === 'fecha' && !is_null($this->fecha)) ||
				($this->modo === 'rango' && !is_null($this->desde) && !is_null($this->hasta))
			)
		);
	}

	public function load(ORequest $req): void {
		$this->id    = $req->getParamInt('id');
		$this->modo  = $req->getParamString('modo');
		$this->fecha = $req->getParamString('fecha');
		$this->desde = $req->getParamString('desde');
		$this->hasta = $req->getParamString('hasta');
	}
}
