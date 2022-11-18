<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class TipoPagoDTO implements ODTO{
  private ?int $id = null;
  private string $nombre = '';
  private ?string $foto = null;
  private ?bool $afecta_caja = null;
  private ?int $orden = null;
  private ?bool $fisico = null;

	public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
	public function getNombre(): string {
		return $this->nombre;
	}
	private function setNombre(string $nombre): void {
		$this->nombre = $nombre;
	}
	public function getFoto(): ?string {
		return $this->foto;
	}
	private function setFoto(?string $foto): void {
		$this->foto = $foto;
	}
	public function getAfectaCaja(): ?bool {
		return $this->afecta_caja;
	}
	public function setAfectaCaja(?bool $afecta_caja): void {
		$this->afecta_caja = $afecta_caja;
	}
	public function getOrden(): ?int {
		return $this->orden;
	}
	private function setOrden(?int $orden): void {
		$this->orden = $orden;
	}
	public function getFisico(): ?bool {
		return $this->fisico;
	}
	private function setFisico(?bool $fisico): void {
		$this->fisico = $fisico;
	}

	public function isValid(): bool {
		return (!is_null($this->getNombre()));
	}

	public function load(ORequest $req): void {
		$this->setId( $req->getParamInt('id') );
		$this->setNombre( $req->getParamString('nombre') );
		$this->setFoto( $req->getParamString('foto') );
		$this->setAfectaCaja( $req->getParamBool('afectaCaja', false) );
		$this->setOrden( $req->getParamInt('orden') );
		$this->setFisico( $req->getParamBool('fisico', true) );
	}
}
