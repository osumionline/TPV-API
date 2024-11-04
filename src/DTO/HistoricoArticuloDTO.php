<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class HistoricoArticuloDTO implements ODTO {
	public ?int    $id         = null;
	public ?string $order_by   = null;
	public ?string $order_sent = null;
	public ?int    $pagina     = null;
	public ?int    $num        = null;

	public function isValid(): bool {
		return (
			!is_null($this->id) &&
			!is_null($this->pagina) &&
			!is_null($this->num)
		);
	}

	public function load(ORequest $req): void {
		$this->id         = $req->getParamInt('id');
		$this->order_by   = $req->getParamString('orderBy');
		$this->order_sent = $req->getParamString('orderSent');
		$this->pagina     = $req->getParamInt('pagina');
		$this->num        = $req->getParamInt('num');
	}
}
