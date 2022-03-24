<?php declare(strict_types=1);

namespace OsumiFramework\App\Utils;

use OsumiFramework\App\DTO\InstallationDTO;

class AppData {
	private bool $loaded = false;
	private string $nombre = '';
	private string $cif = '';
	private string $telefono = '';
	private string $direccion = '';
	private string $email = '';
	private string $twitter = '';
	private string $facebook = '';
	private string $instagram = '';
	private string $web = '';
	private string $tipo_iva = '';
	private array $iva_list = [];
	private array $re_list = [];
	private array $margin_list = [];
	private bool $venta_online = false;
	private string $url_api = '';
	private bool $fecha_cad = false;
	private bool $empleados = false;

	function __construct(string $path) {
		if (file_exists($path)) {
			$data = json_decode(file_get_contents($path), true);
			if ($data !== null) {
				$this->setNombre($data['nombre']);
				$this->setCif($data['cif']);
				$this->setTelefono($data['telefono']);
				$this->setDireccion($data['direccion']);
				$this->setEmail($data['email']);
				$this->setTwitter($data['twitter']);
				$this->setFacebook($data['facebook']);
				$this->setInstagram($data['instagram']);
				$this->setWeb($data['web']);
				$this->setTipoIva($data['tipoIva']);
				$this->setIvaList($data['ivaList']);
				$this->setReList($data['reList']);
				$this->setMarginList($data['marginList']);
				$this->setVentaOnline($data['ventaOnline']);
				$this->setUrlApi($data['urlApi']);
				$this->setFechaCad($data['fechaCad']);
				$this->setEmpleados($data['empleados']);
				$this->setLoaded(true);
			}
		}
	}

	public function fromDTO(InstallationDTO $data): void {
		$this->setNombre($data->getNombre());
		$this->setCif($data->getCif());
		$this->setTelefono($data->getTelefono());
		$this->setDireccion($data->getDireccion());
		$this->setEmail($data->getEmail());
		$this->setTwitter($data->getTwitter());
		$this->setFacebook($data->getFacebook());
		$this->setInstagram($data->getInstagram());
		$this->setWeb($data->getWeb());
		$this->setTipoIva($data->getTipoIva());
		$this->setIvaList($data->getIvaList());
		$this->setReList($data->getReList());
		$this->setMarginList($data->getMarginList());
		$this->setVentaOnline($data->getVentaOnline());
		$this->setUrlApi($data->getUrlApi());
		$this->setFechaCad($data->getFechaCad());
		$this->setEmpleados($data->getEmpleados());
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
		$this->urlApi = $url_api;
	}
	public function getUrlApi(): string {
		return $this->url_api;
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

	public function getJSON(): ?string {
		if (!$this->getLoaded()) {
			return null;
		}
		return json_encode([
			'nombre'      => $this->getNombre(),
			'tipoIva'     => $this->getTipoIva(),
			'ivaList'     => $this->getIvaList(),
			'reList'      => $this->getReList(),
			'marginList'  => $this->getMarginList(),
			'ventaOnline' => $this->getVentaOnline(),
			'fechaCad'    => $this->getFechaCad(),
			'empleados'   => $this->getEmpleados()
		]);
	}

	public function getArray(): ?string {
		if (!$this->getLoaded()) {
			return null;
		}
		return json_encode([
			'nombre'      => $this->getNombre(),
			'cif'         => $this->getCif(),
			'telefono'    => $this->getTelefono(),
			'direccion'   => $this->getDireccion(),
			'email'       => $this->getEmail(),
			'twitter'     => $this->getTwitter(),
			'facebook'    => $this->getFacebook(),
			'instagram'   => $this->getInstagram(),
			'web'         => $this->getWeb(),
			'tipoIva'     => $this->getTipoIva(),
			'ivaList'     => $this->getIvaList(),
			'reList'      => $this->getReList(),
			'marginList'  => $this->getMarginList(),
			'ventaOnline' => $this->getVentaOnline(),
			'urlApi'      => $this->getUrlApi(),
			'fechaCad'    => $this->getFechaCad(),
			'empleados'   => $this->getEmpleados()
		]);
	}
}
