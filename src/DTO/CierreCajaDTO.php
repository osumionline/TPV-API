<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\Core\ODTO;
use Osumi\OsumiFramework\Web\ORequest;

class CierreCajaDTO implements ODTO {
	public ?string $date             = null;
	public ?float  $saldo_inicial    = null;
	public ?float  $importe_efectivo = null;
	public ?float  $salidas_caja     = null;
	public ?float  $saldo_final      = null;
	public ?float  $real             = null;
	public ?int    $importe1c        = null;
	public ?int    $importe2c        = null;
	public ?int    $importe5c        = null;
	public ?int    $importe10c       = null;
	public ?int    $importe20c       = null;
	public ?int    $importe50c       = null;
	public ?int    $importe1         = null;
	public ?int    $importe2         = null;
	public ?int    $importe5         = null;
	public ?int    $importe10        = null;
	public ?int    $importe20        = null;
	public ?int    $importe50        = null;
	public ?int    $importe100       = null;
	public ?int    $importe200       = null;
	public ?int    $importe500       = null;
	public ?float  $retirado         = null;
	public ?float  $entrada          = null;
	public ?array  $tipos            = null;

	public function isValid(): bool {
		return (
			!is_null($this->date) &&
			!is_null($this->real)
		);
	}

	public function load(ORequest $req): void {
		$this->date             = $req->getParamString('date');
		$this->saldo_inicial    = $req->getParamFloat('saldoInicial');
		$this->importe_efectivo = $req->getParamFloat('importeEfectivo');
		$this->salidas_caja     = $req->getParamFloat('salidasCaja');
		$this->real             = $req->getParamFloat('real');
		$this->importe1c        = $req->getParamInt('importe1c');
		$this->importe2c        = $req->getParamInt('importe2c');
		$this->importe5c        = $req->getParamInt('importe5c');
		$this->importe10c       = $req->getParamInt('importe10c');
		$this->importe20c       = $req->getParamInt('importe20c');
		$this->importe50c       = $req->getParamInt('importe50c');
		$this->importe1         = $req->getParamInt('importe1');
		$this->importe2         = $req->getParamInt('importe2');
		$this->importe5         = $req->getParamInt('importe5');
		$this->importe10        = $req->getParamInt('importe10');
		$this->importe20        = $req->getParamInt('importe20');
		$this->importe50        = $req->getParamInt('importe50');
		$this->importe100       = $req->getParamInt('importe100');
		$this->importe200       = $req->getParamInt('importe200');
		$this->importe500       = $req->getParamInt('importe500');
		$this->retirado         = $req->getParamFloat('retirado');
		$this->entrada          = $req->getParamFloat('entrada');
		$this->tipos            = $req->getParam('tipos');
	}
}
