<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class SalidaCajaDTO implements ODTO{
  private ?int $id = null;
  private string $concepto = '';
  private ?string $descripcion = null;
  private ?float $importe = null;

	public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
	public function getConcepto(): string {
		return $this->concepto;
	}
	private function setConcepto(string $concepto): void {
		$this->concepto = $concepto;
	}
	public function getDescripcion(): ?string {
		return $this->descripcion;
	}
	private function setDescripcion(?string $descripcion): void {
		$this->descripcion = $descripcion;
	}
	public function getImporte(): ?float {
		return $this->importe;
	}
	public function setImporte(?float $importe): void {
		$this->importe = $importe;
	}

	public function isValid(): bool {
		return (!is_null($this->getConcepto()));
	}

	public function load(ORequest $req): void {
		$this->setId( $req->getParamInt('id') );
		$this->setConcepto( $req->getParamString('concepto') );
		$this->setDescripcion( $req->getParamString('descripcion') );
		$this->setImporte( $req->getParamFloat('importe') );
	}
}
