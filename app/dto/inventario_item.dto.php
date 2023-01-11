<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class InventarioItemDTO implements ODTO {
  private ?int $id = null;
  private ?int $localizador = null;
  private ?string $marca = null;
  private ?string $referencia = null;
  private ?string $nombre = null;
  private ?int $stock = null;
  private ?float $puc = null;
  private ?float $pvp = null;

  public function getId(): ?int {
		return $this->id;
	}
	private function setId(?int $id): void {
		$this->id = $id;
	}
  public function getLocalizador(): ?int {
		return $this->localizador;
	}
	private function setLocalizador(?int $localizador): void {
		$this->localizador = $localizador;
	}
  public function getMarca(): ?string {
		return $this->marca;
	}
	private function setMarca(?string $marca): void {
		$this->marca = $marca;
	}
  public function getReferencia(): ?string {
		return $this->referencia;
	}
	private function setReferencia(?string $referencia): void {
		$this->referencia = $referencia;
	}
  public function getNombre(): ?string {
		return $this->nombre;
	}
	private function setNombre(?string $nombre): void {
		$this->nombre = $nombre;
	}
  public function getStock(): ?int {
		return $this->stock;
	}
	private function setStock(?int $stock): void {
		$this->stock = $stock;
	}
  public function getPuc(): ?float {
		return $this->puc;
	}
	private function setPuc(?float $puc): void {
		$this->puc = $puc;
	}
  public function getPvp(): ?float {
		return $this->pvp;
	}
	private function setPvp(?float $pvp): void {
		$this->pvp = $pvp;
	}

  public function isValid(): bool {
		return (
      !is_null($this->getId()) &&
      !is_null($this->getStock()) &&
      !is_null($this->getPvp())
    );
	}

  public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
  	$this->setLocalizador( $req->getParamInt('localizador') );
    $this->setMarca( $req->getParamString('marca') );
    $this->setReferencia( $req->getParamString('referencia') );
    $this->setNombre( $req->getParamString('nombre') );
    $this->setStock( $req->getParamInt('stock') );
    $this->setPuc( $req->getParamFloat('puc') );
    $this->setPvp( $req->getParamFloat('pvp') );
  }
}
