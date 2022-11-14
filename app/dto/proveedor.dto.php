<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class ProveedorDTO implements ODTO{
  private ?int $id = null;
  private string $nombre = '';
  private ?int $id_foto = null;
  private ?string $foto = null;
  private string $direccion = '';
  private string $telefono = '';
  private string $email = '';
  private string $web = '';
  private string $observaciones = '';
  private array $marcas = [];

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
  public function getIdFoto(): ?int {
		return $this->id_foto;
	}
	public function setIdFoto(?int $id_foto): void {
		$this->id_foto = $id_foto;
	}
	public function getFoto(): ?string {
		return $this->foto;
	}
	private function setFoto(?string $foto): void {
		$this->foto = $foto;
	}
	public function getDireccion(): string {
		return $this->direccion;
	}
	private function setDireccion(string $direccion): void {
		$this->direccion = $direccion;
	}
	public function getTelefono(): string {
		return $this->telefono;
	}
	private function setTelefono(string $telefono): void {
		$this->telefono = $telefono;
	}
	public function getEmail(): string {
		return $this->email;
	}
	private function setEmail(string $email): void {
		$this->email = $email;
	}
	public function getWeb(): string {
		return $this->web;
	}
	private function setWeb(string $web): void {
		$this->web = $web;
	}
	public function getObservaciones(): string {
		return $this->observaciones;
	}
	private function setObservaciones(string $observaciones): void {
		$this->observaciones = $observaciones;
	}
	public function getMarcas(): array {
		return $this->marcas;
	}
	private function setMarcas(array $marcas): void {
		$this->marcas = $marcas;
	}

	public function isValid(): bool {
		return (!is_null($this->getNombre()));
	}

	public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
		$this->setNombre( $req->getParamString('nombre') );
    $this->setIdFoto( $req->getParamInt('idFoto') );
		$this->setFoto( $req->getParamString('foto') );
		$this->setDireccion( $req->getParamString('direccion') );
		$this->setTelefono( $req->getParamString('telefono') );
		$this->setEmail( $req->getParamString('email') );
		$this->setWeb( $req->getParamString('web') );
		$this->setObservaciones( $req->getParamString('observaciones') );
		$this->setMarcas( $req->getParam('marcas') );
	}
}
