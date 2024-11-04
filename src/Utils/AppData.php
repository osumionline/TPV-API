<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Utils;

use Osumi\OsumiFramework\App\DTO\InstallationDTO;

class AppData {
	private bool $loaded = false;
	private string $nombre = '';
	private string $nombre_comercial = '';
	private string $cif = '';
	private string $telefono = '';
	private string $direccion = '';
	private string $poblacion = '';
	private string $email = '';
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
	private string $backup_api_key = '';
	private bool   $fecha_cad = false;
	private bool   $empleados = false;

	function __construct(string $path = null) {
		if (!is_null($path) && file_exists($path)) {
			$data = json_decode(file_get_contents($path), true);
			if ($data !== null) {
				$this->setNombre(urldecode($data['nombre']));
				$this->setNombreComercial(urldecode($data['nombreComercial']));
				$this->setCif(urldecode($data['cif']));
				$this->setTelefono(urldecode($data['telefono']));
				$this->setDireccion(urldecode($data['direccion']));
				$this->setPoblacion(urldecode($data['poblacion']));
				$this->setEmail(urldecode($data['email']));
				$this->setTwitter(urldecode($data['twitter']));
				$this->setFacebook(urldecode($data['facebook']));
				$this->setInstagram(urldecode($data['instagram']));
				$this->setWeb(urldecode($data['web']));
				$this->setCajaInicial($data['cajaInicial']);
				$this->setTicketInicial($data['ticketInicial']);
				$this->setFacturaInicial($data['facturaInicial']);
				$this->setTipoIva($data['tipoIva']);
				$this->setIvaList($data['ivaList']);
				$this->setReList($data['reList']);
				$this->setMarginList($data['marginList']);
				$this->setVentaOnline($data['ventaOnline']);
				$this->setUrlApi(urldecode($data['urlApi']));
				$this->setSecretApi(urldecode($data['secretApi']));
				$this->setBackupApiKey(urldecode($data['backupApiKey']));
				$this->setFechaCad($data['fechaCad']);
				$this->setEmpleados($data['empleados']);
				$this->setLoaded(true);
			}
		}
	}

	public function fromDTO(InstallationDTO $data): void {
		$this->setNombre($data->nombre);
		$this->setNombreComercial($data->nombre_comercial);
		$this->setCif($data->cif);
		$this->setTelefono($data->telefono);
		$this->setDireccion($data->direccion);
		$this->setPoblacion($data->poblacion);
		$this->setEmail($data->email);
		$this->setTwitter($data->twitter);
		$this->setFacebook($data->facebook);
		$this->setInstagram($data->instagram);
		$this->setWeb($data->web);
		$this->setCajaInicial($data->caja_inicial);
		$this->setTicketInicial($data->ticket_inicial);
		$this->setFacturaInicial($data->factura_inicial);
		$this->setTipoIva($data->tipo_iva);
		$this->setIvaList($data->iva_list);
		$this->setReList($data->re_list);
		$this->setMarginList($data->margin_list);
		$this->setVentaOnline($data->venta_online);
		$this->setUrlApi($data->url_api);
		$this->setSecretApi($data->secret_api);
		$this->setBackupApiKey($data->backup_api_key);
		$this->setFechaCad($data->fecha_cad);
		$this->setEmpleados($data->empleados);
		$this->setLoaded(true);
	}

	private function setLoaded(bool $loaded): void {
		$this->loaded = $loaded;
	}
	public function getLoaded(): bool {
		return $this->loaded;
	}
	private function setNombre(string $nombre): void {
		$this->nombre = $nombre;
	}
	public function getNombre(): string {
		return $this->nombre;
	}
	private function setNombreComercial(string $nombre_comercial): void {
		$this->nombre_comercial = $nombre_comercial;
	}
	public function getNombreComercial(): string {
		return $this->nombre_comercial;
	}
	private function setCif(string $cif): void {
		$this->cif = $cif;
	}
	public function getCif(): string {
		return $this->cif;
	}
	private function setTelefono(string $telefono): void {
		$this->telefono = $telefono;
	}
	public function getTelefono(): string {
		return $this->telefono;
	}
	private function setDireccion(string $direccion): void {
		$this->direccion = $direccion;
	}
	public function getDireccion(): string {
		return $this->direccion;
	}
	private function setPoblacion(string $poblacion): void {
		$this->poblacion = $poblacion;
	}
	public function getPoblacion(): string {
		return $this->poblacion;
	}
	private function setEmail(string $email): void {
		$this->email = $email;
	}
	public function getEmail(): string {
		return $this->email;
	}
	private function setTwitter(string $twitter): void {
		$this->twitter = $twitter;
	}
	public function getTwitter(): string {
		return $this->twitter;
	}
	private function setFacebook(string $facebook): void {
		$this->facebook = $facebook;
	}
	public function getFacebook(): string {
		return $this->facebook;
	}
	private function setInstagram(string $instagram): void {
		$this->instagram = $instagram;
	}
	public function getInstagram(): string {
		return $this->instagram;
	}
	private function setWeb(string $web): void {
		$this->web = $web;
	}
	public function getWeb(): string {
		return $this->web;
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
	private function setTipoIva(string $tipo_iva): void {
		$this->tipo_iva = $tipo_iva;
	}
	public function getTipoIva(): string {
		return $this->tipo_iva;
	}
	private function setIvaList(array $iva_list): void {
		$this->iva_list = $iva_list;
	}
	public function getIvaList(): array {
		return $this->iva_list;
	}
	private function setReList(array $re_list): void {
		$this->re_list = $re_list;
	}
	public function getReList(): array {
		return $this->re_list;
	}
	private function setMarginList(array $margin_list): void {
		$this->margin_list = $margin_list;
	}
	public function getMarginList(): array {
		return $this->margin_list;
	}
	private function setVentaOnline(bool $venta_online): void {
		$this->venta_online = $venta_online;
	}
	public function getVentaOnline(): bool {
		return $this->venta_online;
	}
	private function setUrlApi(string $url_api): void {
		$this->url_api = $url_api;
	}
	public function getUrlApi(): string {
		return $this->url_api;
	}
	private function setSecretApi(string $secret_api): void {
		$this->secret_api = $secret_api;
	}
	public function getSecretApi(): string {
		return $this->secret_api;
	}
	private function setBackupApiKey(string $backup_api_key): void {
		$this->backup_api_key = $backup_api_key;
	}
	public function getBackupApiKey(): string {
		return $this->backup_api_key;
	}
	private function setFechaCad(bool $fecha_cad): void {
		$this->fecha_cad = $fecha_cad;
	}
	public function getFechaCad(): bool {
		return $this->fecha_cad;
	}
	private function setEmpleados(bool $empleados): void {
		$this->empleados = $empleados;
	}
	public function getEmpleados(): bool {
		return $this->empleados;
	}

	public function getSocial(): array {
		$social = [];
		if ($this->getTwitter() !== '') {
			$social[] = ['twitter', $this->getTwitter()];
		}
		if ($this->getFacebook() !== '') {
			$social[] = ['facebook', $this->getFacebook()];
		}
		if ($this->getInstagram() !== '') {
			$social[] = ['instagram', $this->getInstagram()];
		}
		if ($this->getWeb() !== '') {
			$social[] = ['web', $this->getWeb()];
		}
		return $social;
	}

	public function getJSON(): ?string {
		if (!$this->getLoaded()) {
			return null;
		}
		return json_encode([
			'nombre'          => $this->getNombre(),
			'nombreComercial' => $this->getNombreComercial(),
			'tipoIva'         => $this->getTipoIva(),
			'ivaList'         => $this->getIvaList(),
			'reList'          => $this->getReList(),
			'marginList'      => $this->getMarginList(),
			'ventaOnline'     => $this->getVentaOnline(),
			'fechaCad'        => $this->getFechaCad(),
			'empleados'       => $this->getEmpleados()
		]);
	}

	public function getArray(bool $encoded = false): ?string {
		if (!$this->getLoaded()) {
			return null;
		}
		return json_encode([
			'nombre'          => $encoded ? urlencode($this->getNombre()) : $this->getNombre(),
			'nombreComercial' => $encoded ? urlencode($this->getNombreComercial()) : $this->getNombreComercial(),
			'cif'             => $encoded ? urlencode($this->getCif()) : $this->getCif(),
			'telefono'        => $encoded ? urlencode($this->getTelefono()) : $this->getTelefono(),
			'direccion'       => $encoded ? urlencode($this->getDireccion()) : $this->getDireccion(),
			'poblacion'       => $encoded ? urlencode($this->getPoblacion()) : $this->getPoblacion(),
			'email'           => $encoded ? urlencode($this->getEmail()) : $this->getEmail(),
			'twitter'         => $encoded ? urlencode($this->getTwitter()) : $this->getTwitter(),
			'facebook'        => $encoded ? urlencode($this->getFacebook()) : $this->getFacebook(),
			'instagram'       => $encoded ? urlencode($this->getInstagram()) : $this->getInstagram(),
			'web'             => $encoded ? urlencode($this->getWeb()) : $this->getWeb(),
			'cajaInicial'     => $this->getCajaInicial(),
			'ticketInicial'   => $this->getTicketInicial(),
			'facturaInicial'  => $this->getFacturaInicial(),
			'tipoIva'         => $this->getTipoIva(),
			'ivaList'         => $this->getIvaList(),
			'reList'          => $this->getReList(),
			'marginList'      => $this->getMarginList(),
			'ventaOnline'     => $this->getVentaOnline(),
			'urlApi'          => $encoded ? urlencode($this->getUrlApi()) : $this->getUrlApi(),
			'secretApi'       => $encoded ? urlencode($this->getSecretApi()) : $this->getSecretApi(),
			'backupApiKey'    => $encoded ? urlencode($this->getBackupApiKey()) : $this->getBackupApiKey(),
			'fechaCad'        => $this->getFechaCad(),
			'empleados'       => $this->getEmpleados()
		]);
	}
}
