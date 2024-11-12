<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class CaducidadesDTO implements ODTO {
	public ?int    $year       = null;
	public ?int    $month      = null;
	public ?int    $pagina     = null;
	public ?int    $num        = null;
	public ?int    $id_marca   = null;
	public ?string $nombre     = null;
	public ?string $order_by   = null;
	public ?string $order_sent = null;

	public function isValid(): bool {
		return true;
	}

	public function load(ORequest $req): void {
		$this->year       = $req->getParamInt('year');
		$this->month      = $req->getParamInt('month');
		$this->pagina     = $req->getParamInt('pagina');
		$this->num        = $req->getParamInt('num');
		$this->id_marca   = $req->getParamInt('idMarca');
		$this->nombre     = $req->getParamString('nombre');
		$this->order_by   = $req->getParamString('orderBy');
		$this->order_sent = $req->getParamString('orderSent');
	}
}
