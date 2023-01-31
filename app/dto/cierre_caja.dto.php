<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class CierreCajaDTO implements ODTO {
  private ?string $date             = null;
  private ?float  $saldo_inicial    = null;
  private ?float  $importe_efectivo = null;
  private ?float  $salidas_caja     = null;
  private ?float  $saldo_final      = null;
  private ?float  $real             = null;
  private ?int    $importe1c        = null;
  private ?int    $importe2c        = null;
  private ?int    $importe5c        = null;
  private ?int    $importe10c       = null;
  private ?int    $importe20c       = null;
  private ?int    $importe50c       = null;
  private ?int    $importe1         = null;
  private ?int    $importe2         = null;
  private ?int    $importe5         = null;
  private ?int    $importe10        = null;
  private ?int    $importe20        = null;
  private ?int    $importe50        = null;
  private ?int    $importe100       = null;
  private ?int    $importe200       = null;
  private ?int    $importe500       = null;
  private ?float  $retirado         = null;
  private ?array  $tipos            = null;

  public function getDate(): ?string {
		return $this->date;
	}
	private function setDate(?string $date): void {
		$this->date = $date;
	}
  public function getSaldoInicial(): ?float {
		return $this->saldo_inicial;
	}
	private function setSaldoInicial(?float $saldo_inicial): void {
		$this->saldo_inicial = $saldo_inicial;
	}
  public function getImporteEfectivo(): ?float {
		return $this->importe_efectivo;
	}
	private function setImporteEfectivo(?float $importe_efectivo): void {
		$this->importe_efectivo = $importe_efectivo;
	}
  public function getSalidasCaja(): ?float {
		return $this->salidas_caja;
	}
	private function setSalidasCaja(?float $salidas_caja): void {
		$this->salidas_caja = $salidas_caja;
	}
  public function getSaldoFinal(): ?float {
		return $this->saldo_final;
	}
	private function setSaldoFinal(?float $saldo_final): void {
		$this->saldo_final = $saldo_final;
	}
  public function getReal(): ?float {
		return $this->real;
	}
	private function setReal(?float $real): void {
		$this->real = $real;
	}
  public function getImporte1c(): ?int {
		return $this->importe1c;
	}
	private function setImporte1c(?int $importe1c): void {
		$this->importe1c = $importe1c;
	}
  public function getImporte2c(): ?int {
		return $this->importe2c;
	}
	private function setImporte2c(?int $importe2c): void {
		$this->importe2c = $importe2c;
	}
  public function getImporte5c(): ?int {
		return $this->importe5c;
	}
	private function setImporte5c(?int $importe5c): void {
		$this->importe5c = $importe5c;
	}
  public function getImporte10c(): ?int {
		return $this->importe10c;
	}
	private function setImporte10c(?int $importe10c): void {
		$this->importe10c = $importe10c;
	}
  public function getImporte20c(): ?int {
		return $this->importe20c;
	}
	private function setImporte20c(?int $importe20c): void {
		$this->importe20c = $importe20c;
	}
  public function getImporte50c(): ?int {
		return $this->importe50c;
	}
	private function setImporte50c(?int $importe50c): void {
		$this->importe50c = $importe50c;
	}
  public function getImporte1(): ?int {
		return $this->importe1;
	}
	private function setImporte1(?int $importe1): void {
		$this->importe1 = $importe1;
	}
  public function getImporte2(): ?int {
		return $this->importe2;
	}
	private function setImporte2(?int $importe2): void {
		$this->importe2 = $importe2;
	}
  public function getImporte5(): ?int {
		return $this->importe5;
	}
	private function setImporte5(?int $importe5): void {
		$this->importe5 = $importe5;
	}
  public function getImporte10(): ?int {
		return $this->importe10;
	}
	private function setImporte10(?int $importe10): void {
		$this->importe10 = $importe10;
	}
  public function getImporte20(): ?int {
		return $this->importe20;
	}
	private function setImporte20(?int $importe20): void {
		$this->importe20 = $importe20;
	}
  public function getImporte50(): ?int {
		return $this->importe50;
	}
	private function setImporte50(?int $importe50): void {
		$this->importe50 = $importe50;
	}
  public function getImporte100(): ?int {
		return $this->importe100;
	}
	private function setImporte100(?int $importe100): void {
		$this->importe100 = $importe100;
	}
  public function getImporte200(): ?int {
		return $this->importe200;
	}
	private function setImporte200(?int $importe200): void {
		$this->importe200 = $importe200;
	}
  public function getImporte500(): ?int {
		return $this->importe500;
	}
	private function setImporte500(?int $importe500): void {
		$this->importe500 = $importe500;
	}
  public function getRetirado(): ?float {
		return $this->retirado;
	}
	private function setRetirado(?float $retirado): void {
		$this->retirado = $retirado;
	}
  public function getTipos(): ?array {
		return $this->tipos;
	}
	private function setTipos(?array $tipos): void {
		$this->tipos = $tipos;
	}

  public function isValid(): bool {
		return (!is_null($this->getDate()) && !is_null($this->getReal()));
	}

	public function load(ORequest $req): void {
    $this->setDate( $req->getParamString('date') );
    $this->setSaldoInicial( $req->getParamFloat('saldoInicial') );
    $this->setImporteEfectivo( $req->getParamFloat('importeEfectivo') );
    $this->setSalidasCaja( $req->getParamFloat('salidasCaja') );
    $this->setReal( $req->getParamFloat('real') );
    $this->setImporte1c( $req->getParamInt('importe1c') );
    $this->setImporte2c( $req->getParamInt('importe2c') );
    $this->setImporte5c( $req->getParamInt('importe5c') );
    $this->setImporte10c( $req->getParamInt('importe10c') );
    $this->setImporte20c( $req->getParamInt('importe20c') );
    $this->setImporte50c( $req->getParamInt('importe50c') );
    $this->setImporte1( $req->getParamInt('importe1') );
    $this->setImporte2( $req->getParamInt('importe2') );
    $this->setImporte5( $req->getParamInt('importe5') );
    $this->setImporte10( $req->getParamInt('importe10') );
    $this->setImporte20( $req->getParamInt('importe20') );
    $this->setImporte50( $req->getParamInt('importe50') );
    $this->setImporte100( $req->getParamInt('importe100') );
    $this->setImporte200( $req->getParamInt('importe200') );
    $this->setImporte500( $req->getParamInt('importe500') );
    $this->setRetirado( $req->getParamFloat('retirado') );
    $this->setTipos( $req->getParam('tipos') );
  }
}
