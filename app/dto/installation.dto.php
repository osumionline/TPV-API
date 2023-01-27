<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class InstallationDTO implements ODTO{
	private string $nombre = '';
	private string $nombre_comercial = '';
	private string $cif = '';
	private string $telefono = '';
	private string $direccion = '';
	private string $poblacion = '';
	private string $email = '';
	private string $logo = '';
	private string $nombre_empleado = '';
	private string $pass = '';
	private string $color = '';
	private string $twitter = '';
	private string $facebook = '';
	private string $instagram = '';
	private string $web = '';
	private float  $caja_inicial = 0;
  private int    $ticket_inicial = 1;
  private int    $factura_inicial = 1;
	private string $tipo_iva = '';
	private array  $iva_list = [];
	private array  $re_list = [];
	private array  $margin_list = [];
	private bool   $venta_online = false;
	private string $url_api = '';
	private string $secret_api = '';
	private bool   $fecha_cad = false;
	private bool   $empleados = false;

	public function getNombre(): string {
		return $this->nombre;
	}
	private function setNombre(string $nombre): void {
		$this->nombre = $nombre;
	}
	public function getNombreComercial(): string {
		return $this->nombre_comercial;
	}
	private function setNombreComercial(string $nombre_comercial): void {
		$this->nombre_comercial = $nombre_comercial;
	}
	public function getCif(): string {
		return $this->cif;
	}
	private function setCif(string $cif): void {
		$this->cif = $cif;
	}
	public function getTelefono(): string {
		return $this->telefono;
	}
	private function setTelefono(string $telefono): void {
		$this->telefono = $telefono;
	}
	public function getDireccion(): string {
		return $this->direccion;
	}
	private function setDireccion(string $direccion): void {
		$this->direccion = $direccion;
	}
	private function setPoblacion(string $poblacion): void {
		$this->poblacion = $poblacion;
	}
	public function getPoblacion(): string {
		return $this->poblacion;
	}
	public function getEmail(): string {
		return $this->email;
	}
	private function setEmail(string $email): void {
		$this->email = $email;
	}
	public function getLogo(): string {
		return $this->logo;
	}
	private function setLogo(string $logo): void {
		$this->logo = $logo;
	}
	public function getNombreEmpleado(): string {
		return $this->nombre_empleado;
	}
	private function setNombreEmpleado(string $nombre_empleado): void {
		$this->nombre_empleado = $nombre_empleado;
	}
	public function getPass(): string {
		return $this->pass;
	}
	private function setPass(string $pass): void {
		$this->pass = $pass;
	}
	public function getColor(): string {
		return $this->color;
	}
	private function setColor(string $color): void {
		$this->color = $color;
	}
	public function getTwitter(): string {
		return $this->twitter;
	}
	private function setTwitter(string $twitter): void {
		$this->twitter = $twitter;
	}
	public function getFacebook(): string {
		return $this->facebook;
	}
	private function setFacebook(string $facebook): void {
		$this->facebook = $facebook;
	}
	public function getInstagram(): string {
		return $this->instagram;
	}
	private function setInstagram(string $instagram): void {
		$this->instagram = $instagram;
	}
	public function getWeb(): string {
		return $this->web;
	}
	private function setWeb(string $web): void {
		$this->web = $web;
	}
	public function getCajaInicial(): float {
		return $this->caja_inicial;
	}
	private function setCajaInicial(float $caja_inicial): void {
		$this->caja_inicial = $caja_inicial;
	}
	public function getTicketInicial(): int {
		return $this->ticket_inicial;
	}
	private function setTicketInicial(int $ticket_inicial): void {
		$this->ticket_inicial = $ticket_inicial;
	}
	public function getFacturaInicial(): int {
		return $this->factura_inicial;
	}
	private function setFacturaInicial(int $factura_inicial): void {
		$this->factura_inicial = $factura_inicial;
	}
	public function getTipoIva(): string {
		return $this->tipo_iva;
	}
	private function setTipoIva(string $tipo_iva): void {
		$this->tipo_iva = $tipo_iva;
	}
	public function getIvaList(): array {
		return $this->iva_list;
	}
	private function setIvaList(array $iva_list): void {
		$this->iva_list = $iva_list;
	}
	public function getReList(): array {
		return $this->re_list;
	}
	private function setReList(array $re_list): void {
		$this->re_list = $re_list;
	}
	public function getMarginList(): array {
		return $this->margin_list;
	}
	private function setMarginList(array $margin_list): void {
		$this->margin_list = $margin_list;
	}
	public function getVentaOnline(): bool {
		return $this->venta_online;
	}
	private function setVentaOnline(bool $venta_online): void {
		$this->venta_online = $venta_online;
	}
	public function getUrlApi(): string {
		return $this->url_api;
	}
	private function setUrlApi(string $url_api): void {
		$this->url_api = $url_api;
	}
	public function getSecretApi(): string {
		return $this->secret_api;
	}
	private function setSecretApi(string $secret_api): void {
		$this->secret_api = $secret_api;
	}
	public function getFechaCad(): bool {
		return $this->fecha_cad;
	}
	private function setFechaCad(bool $fecha_cad): void {
		$this->fecha_cad = $fecha_cad;
	}
	public function getEmpleados(): bool {
		return $this->empleados;
	}
	private function setEmpleados(bool $empleados): void {
		$this->empleados = $empleados;
	}

	public function isValid(): bool {
		return (
			$this->getNombre() != '' &&
			$this->getNombreComercial() != '' &&
			$this->getCif() != '' &&
			$this->getLogo() != '' &&
			$this->getColor() != '' &&
			$this->getTipoIva() != '' &&
			count($this->getIvaList()) > 0 &&
			(!$this->getVentaOnline() || ($this->getVentaOnline() && $this->getUrlApi() != '' && $this->getSecretApi()))
		);
	}

	public function load(ORequest $req): void {
		$this->setNombre( $req->getParamString('nombre') );
		$this->setNombreComercial( $req->getParamString('nombreComercial') );
		$this->setCif( $req->getParamString('cif') );
		$this->setTelefono( $req->getParamString('telefono') );
		$this->setDireccion( $req->getParamString('direccion') );
		$this->setPoblacion( $req->getParamString('poblacion') );
		$this->setEmail( $req->getParamString('email') );
		$this->setLogo( $req->getParamString('logo') );
		$this->setNombreEmpleado( $req->getParamString('nombreEmpleado') );
		$this->setPass( $req->getParamString('pass') );
		$this->setColor( $req->getParamString('color') );
		$this->setTwitter( $req->getParamString('twitter') );
		$this->setFacebook( $req->getParamString('facebook') );
		$this->setInstagram( $req->getParamString('instagram') );
		$this->setWeb( $req->getParamString('web') );
		$this->setCajaInicial( $req->getParamFloat('cajaInicial') );
		$this->setTicketInicial( $req->getParamInt('ticketInicial') );
		$this->setFacturaInicial( $req->getParamInt('facturaInicial') );
		$this->setTipoIva( $req->getParamString('tipoIva') );
		$this->setIvaList( $req->getParam('ivaList') );
		$this->setReList( $req->getParam('reList') );
		$this->setMarginList( $req->getParam('marginList') );
		$this->setVentaOnline( $req->getParamBool('ventaOnline', false) );
		$this->setUrlApi( $req->getParamString('urlApi') );
		$this->setSecretApi( $req->getParamString('secretApi') );
		$this->setFechaCad( $req->getParamBool('fechaCad', false) );
		$this->setEmpleados( $req->getParamBool('empleados', false) );
	}
}
