<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class FacturaDTO implements ODTO {
	public ?int   $id         = null;
	public ?int   $id_cliente = null;
	public ?array $ventas     = null;
	public bool   $imprimir   = false;

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
