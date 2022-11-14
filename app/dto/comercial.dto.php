<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class ComercialDTO implements ODTO{
  private ?int $id = null;
  private ?int $id_proveedor = null;
  private ?string $nombre = '';
  private ?string $telefono = '';
  private ?string $email = '';
  private ?string $observaciones = '';

	public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
  public function getIdProveedor(): ?int {
		return $this->id_proveedor;
	}
	public function setIdProveedor(?int $id_proveedor): void {
		$this->id_proveedor = $id_proveedor;
	}
	public function getNombre(): ?string {
		return $this->nombre;
	}
	private function setNombre(?string $nombre): void {
		$this->nombre = $nombre;
	}
	public function getTelefono(): ?string {
		return $this->telefono;
	}
	private function setTelefono(?string $telefono): void {
		$this->telefono = $telefono;
	}
	public function getEmail(): ?string {
		return $this->email;
	}
	private function setEmail(?string $email): void {
		$this->email = $email;
	}
	public function getObservaciones(): ?string {
		return $this->observaciones;
	}
	private function setObservaciones(?string $observaciones): void {
		$this->observaciones = $observaciones;
	}

	public function isValid(): bool {
		return (!is_null($this->getNombre()));
	}

	public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
    $this->setIdProveedor( $req->getParamInt('idProveedor') );
		$this->setNombre( $req->getParamString('nombre') );
		$this->setTelefono( $req->getParamString('telefono') );
		$this->setEmail( $req->getParamString('email') );
		$this->setObservaciones( $req->getParamString('observaciones') );
	}
}
