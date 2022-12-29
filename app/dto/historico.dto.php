<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class HistoricoDTO implements ODTO {
  private ?int $id = null;
  private ?string $modo = null;
  private ?string $fecha = null;
  private ?string $desde = null;
  private ?string $hasta = null;

  public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
  public function getModo(): ?string {
		return $this->modo;
	}
	private function setModo(?string $modo): void {
		$this->modo = $modo;
	}
  public function getFecha(): ?string {
		return $this->fecha;
	}
	private function setFecha(?string $fecha): void {
		$this->fecha = $fecha;
	}
  public function getDesde(): ?string {
		return $this->desde;
	}
	private function setDesde(?string $desde): void {
		$this->desde = $desde;
	}
  public function getHasta(): ?string {
		return $this->hasta;
	}
	private function setHasta(?string $hasta): void {
		$this->hasta = $hasta;
	}

  public function isValid(): bool {
		return (
      !is_null($this->getModo()) &&
      (
        ($this->getModo() == 'id' && !is_null($this->getId())) ||
        ($this->getModo() == 'fecha' && !is_null($this->getFecha())) ||
        ($this->getModo() == 'rango' && !is_null($this->getDesde()) && !is_null($this->getHasta()))
      )
    );
	}

  public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
  	$this->setModo( $req->getParamString('modo') );
    $this->setFecha( $req->getParamString('fecha') );
    $this->setDesde( $req->getParamString('desde') );
    $this->setHasta( $req->getParamString('hasta') );
  }
}
