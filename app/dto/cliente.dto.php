<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class ClienteDTO implements ODTO{
  private ?int $id = null;
	private string $nombre_apellidos = '';
	private string $dni_cif = '';
	private ?string $telefono = null;
	private ?string $email = null;
	private ?string $direccion = null;
	private ?string $codigo_postal = null;
	private ?string $poblacion = null;
	private ?int $provincia = null;
	private bool $fact_igual = true;
	private ?string $fact_nombre_apellidos = null;
	private ?string $fact_dni_cif = null;
	private ?string $fact_telefono = null;
	private ?string $fact_email = null;
	private ?string $fact_direccion = null;
	private ?string $fact_codigo_postal = null;
	private ?string $fact_poblacion = null;
	private ?int $fact_provincia = null;
	private ?string $observaciones = null;

	public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
  public function getNombreApellidos(): string {
		return $this->nombre_apellidos;
	}
	private function setNombreApellidos(string $nombre_apellidos): void {
		$this->nombre_apellidos = $nombre_apellidos;
	}
  public function getDniCif(): string {
		return $this->dni_cif;
	}
	private function setDniCif(string $dni_cif): void {
		$this->dni_cif = $dni_cif;
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
  public function getDireccion(): ?string {
		return $this->direccion;
	}
	private function setDireccion(?string $direccion): void {
		$this->direccion = $direccion;
	}
  public function getCodigoPostal(): ?string {
		return $this->codigo_postal;
	}
	private function setCodigoPostal(?string $codigo_postal): void {
		$this->codigo_postal = $codigo_postal;
	}
  public function getPoblacion(): ?string {
		return $this->poblacion;
	}
	private function setPoblacion(?string $poblacion): void {
		$this->poblacion = $poblacion;
	}
  public function getProvincia(): ?int {
		return $this->provincia;
	}
	private function setProvincia(?int $provincia): void {
		$this->provincia = $provincia;
	}
  public function getFactIgual(): bool {
		return $this->fact_igual;
	}
	private function setFactIgual(bool $fact_igual): void {
		$this->fact_igual = $fact_igual;
	}
  public function getFactNombreApellidos(): ?string {
		return $this->fact_nombre_apellidos;
	}
	private function setFactNombreApellidos(?string $fact_nombre_apellidos): void {
		$this->fact_nombre_apellidos = $fact_nombre_apellidos;
	}
  public function getFactDniCif(): ?string {
		return $this->fact_dni_cif;
	}
	private function setFactDniCif(?string $fact_dni_cif): void {
		$this->fact_dni_cif = $fact_dni_cif;
	}
  public function getFactTelefono(): ?string {
		return $this->fact_telefono;
	}
	private function setFactTelefono(?string $fact_telefono): void {
		$this->fact_telefono = $fact_telefono;
	}
  public function getFactEmail(): ?string {
		return $this->fact_email;
	}
	private function setFactEmail(?string $fact_email): void {
		$this->fact_email = $fact_email;
	}
  public function getFactDireccion(): ?string {
		return $this->fact_direccion;
	}
	private function setFactDireccion(?string $fact_direccion): void {
		$this->fact_direccion = $fact_direccion;
	}
  public function getFactCodigoPostal(): ?string {
		return $this->fact_codigo_postal;
	}
	private function setFactCodigoPostal(?string $fact_codigo_postal): void {
		$this->fact_codigo_postal = $fact_codigo_postal;
	}
  public function getFactPoblacion(): ?string {
		return $this->fact_poblacion;
	}
	private function setFactPoblacion(?string $fact_poblacion): void {
		$this->fact_poblacion = $fact_poblacion;
	}
  public function getFactProvincia(): ?int {
		return $this->fact_provincia;
	}
	private function setFactProvincia(?int $fact_provincia): void {
		$this->fact_provincia = $fact_provincia;
	}
  public function getObservaciones(): string {
		return $this->observaciones;
	}
	private function setObservaciones(string $observaciones): void {
		$this->observaciones = $observaciones;
	}

	public function isValid(): bool {
		return (!is_null($this->getNombreApellidos()));
	}

	public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
  	$this->setNombreApellidos( $req->getParamString('nombreApellidos') );
  	$this->setDniCif( $req->getParamString('dniCif') );
  	$this->setTelefono( $req->getParamString('telefono') );
  	$this->setEmail( $req->getParamString('email') );
  	$this->setDireccion( $req->getParamString('direccion') );
  	$this->setCodigoPostal( $req->getParamString('codigoPostal') );
  	$this->setPoblacion( $req->getParamString('poblacion') );
  	$this->setProvincia( $req->getParamInt('provincia') );
  	$this->setFactIgual( $req->getParamBool('factIgual') );
  	$this->setFactNombreApellidos( $req->getParamString('factNombreApellidos') );
  	$this->setFactDniCif( $req->getParamString('factDniCif') );
  	$this->setFactTelefono( $req->getParamString('factTelefono') );
  	$this->setFactEmail( $req->getParamString('factEmail') );
  	$this->setFactDireccion( $req->getParamString('factDireccion') );
  	$this->setFactCodigoPostal( $req->getParamString('factCodigoPostal') );
  	$this->setFactPoblacion( $req->getParamString('factPoblacion') );
  	$this->setFactProvincia( $req->getParamInt('factProvincia') );
  	$this->setObservaciones( $req->getParamString('observaciones') );
	}
}
