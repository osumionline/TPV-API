<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class HistoricoDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public ?string $modo = null;

	#[ODTOField(required: false)]
	public ?string $fecha = null;

	#[ODTOField(required: false)]
	public ?string $desde = null;

	#[ODTOField(required: false)]
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
