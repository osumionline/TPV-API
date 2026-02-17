<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class FacturaDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public ?int $idCliente = null;

	#[ODTOField(required: true)]
	public ?array $ventas = null;

	#[ODTOField(required: false)]
	public bool $imprimir = false;

	public function isValid(): bool {
		return (
			!is_null($this->id_cliente) &&
			!is_null($this->ventas) &&
			is_array($this->ventas) &&
			count($this->ventas) > 0
		);
	}

	public function load(ORequest $req): void {
		$this->id         = $req->getParamInt('id');
		$this->id_cliente = $req->getParamInt('idCliente');
		$this->ventas     = $req->getParam('ventas');
		$this->imprimir   = $req->getParamBool('imprimir');
	}
}
