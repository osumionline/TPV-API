<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class CierreCajaDTO implements ODTO {
  private ?string $date = null;
  private ?float $importe_efectivo = null;
  private ?float $importe_total = null;
  private ?float $importe_real = null;
  private ?float $importe_retirado = null;
  private ?float $saldo_inicial = null;
  private ?array $tipos = null;

  public function getDate(): ?string {
		return $this->date;
	}
	private function setDate(?string $date): void {
		$this->date = $date;
	}
  public function getImporteEfectivo(): ?float {
		return $this->importe_efectivo;
	}
	private function setImporteEfectivo(?float $importe_efectivo): void {
		$this->importe_efectivo = $importe_efectivo;
	}
  public function getImporteTotal(): ?float {
		return $this->importe_total;
	}
	private function setImporteTotal(?float $importe_total): void {
		$this->importe_total = $importe_total;
	}
  public function getImporteReal(): ?float {
		return $this->importe_real;
	}
	private function setImporteReal(?float $importe_real): void {
		$this->importe_real = $importe_real;
	}
  public function getImporteRetirado(): ?float {
		return $this->importe_retirado;
	}
	private function setImporteRetirado(?float $importe_retirado): void {
		$this->importe_retirado = $importe_retirado;
	}
  public function getSaldoInicial(): ?float {
		return $this->saldo_inicial;
	}
	private function setSaldoInicial(?float $saldo_inicial): void {
		$this->saldo_inicial = $saldo_inicial;
	}
  public function getTipos(): ?array {
		return $this->tipos;
	}
	private function setTipos(?array $tipos): void {
		$this->tipos = $tipos;
	}

  public function isValid(): bool {
		return (!is_null($this->getDate()) && !is_null($this->getImporteReal()));
	}

	public function load(ORequest $req): void {
    $this->setDate( $req->getParamString('date') );
    $this->setImporteEfectivo( $req->getParamFloat('importeEfectivo') );
    $this->setImporteTotal( $req->getParamFloat('importeTotal') );
    $this->setImporteReal( $req->getParamFloat('real') );
    $this->setSaldoInicial( $req->getParamFloat('saldoInicial') );
    $this->setTipos( $req->getParam('tipos') );
  }
}
