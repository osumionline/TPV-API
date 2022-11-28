<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class ArticuloDTO implements ODTO{
	private ?int $id = null;
	private ?int $localizador = null;
	private string $nombre = '';
	private ?int $id_categoria = null;
	private ?int $id_marca = null;
	private ?int $id_proveedor = null;
	private string $referencia = '';
	private float $palb = 0;
	private float $puc = 0;
	private float $pvp = 0;
	private int $iva = 0;
	private float $re = 0;
	private float $margen = 0;
	private int $stock = 0;
	private int $stock_min = 0;
	private int $stock_max = 0;
	private int $lote_optimo = 0;
	private bool $venta_online = false;
	private ?string $fecha_caducidad = null;
	private bool $mostrar_en_web = false;
	private string $desc_corta = '';
	private string $descripcion = '';
	private string $observaciones = '';
	private bool $mostrar_obs_pedidos = false;
	private bool $mostrar_obs_ventas = false;
	private array $codigos_barras = [];
	private array $fotos_list = [];
	private string $nombre_status = 'ok';

	public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
	public function getLocalizador(): ?int {
		return $this->localizador;
	}
	public function setLocalizador(?int $localizador): void {
		$this->localizador = $localizador;
	}
	public function getNombre(): string {
		return $this->nombre;
	}
	private function setNombre(string $nombre): void {
		$this->nombre = $nombre;
	}
	public function getIdCategoria(): ?int {
		return $this->id_categoria;
	}
	private function setIdCategoria(?int $id_categoria): void {
		$this->id_categoria = $id_categoria;
	}
	public function getIdMarca(): ?int {
		return $this->id_marca;
	}
	private function setIdMarca(?int $id_marca): void {
		$this->id_marca = $id_marca;
	}
	public function getIdProveedor(): ?int {
		return $this->id_proveedor;
	}
	private function setIdProveedor(?int $id_proveedor): void {
		$this->id_proveedor = $id_proveedor;
	}
	public function getReferencia(): string {
		return $this->referencia;
	}
	private function setReferencia(string $referencia): void {
		$this->referencia = $referencia;
	}
	public function getPalb(): float {
		return $this->palb;
	}
	private function setPalb(float $palb): void {
		$this->palb = $palb;
	}
	public function getPuc(): float {
		return $this->puc;
	}
	private function setPuc(float $puc): void {
		$this->puc = $puc;
	}
	public function getPvp(): float {
		return $this->pvp;
	}
	private function setPvp(float $pvp): void {
		$this->pvp = $pvp;
	}
	public function getIva(): int {
		return $this->iva;
	}
	private function setIva(int $iva): void {
		$this->iva = $iva;
	}
	public function getRe(): float {
		return $this->re;
	}
	private function setRe(float $re): void {
		$this->re = $re;
	}
	public function getMargen(): float {
		return $this->margen;
	}
	private function setMargen(float $margen): void {
		$this->margen = $margen;
	}
	public function getStock(): int {
		return $this->stock;
	}
	private function setStock(int $stock): void {
		$this->stock = $stock;
	}
	public function getStockMin(): int {
		return $this->stock_min;
	}
	private function setStockMin(int $stock_min): void {
		$this->stock_min = $stock_min;
	}
	public function getStockMax(): int {
		return $this->stock_max;
	}
	private function setStockMax(int $stock_max): void {
		$this->stock_max = $stock_max;
	}
	public function getLoteOptimo(): int {
		return $this->lote_optimo;
	}
	private function setLoteOptimo(int $lote_optimo): void {
		$this->lote_optimo = $lote_optimo;
	}
	public function getVentaOnline(): bool {
		return $this->venta_online;
	}
	private function setVentaOnline(bool $venta_online): void {
		$this->venta_online = $venta_online;
	}
	public function getFechaCaducidad(): ?string {
		return $this->fecha_caducidad;
	}
	private function setFechaCaducidad(?string $fecha_caducidad): void {
		$this->fecha_caducidad = $fecha_caducidad;
	}
	public function getMostrarEnWeb(): bool {
		return $this->mostrar_en_web;
	}
	private function setMostrarEnWeb(bool $mostrar_en_web): void {
		$this->mostrar_en_web = $mostrar_en_web;
	}
	public function getDescCorta(): string {
		return $this->desc_corta;
	}
	private function setDescCorta(string $desc_corta): void {
		$this->desc_corta = $desc_corta;
	}
	public function getDescripcion(): string {
		return $this->descripcion;
	}
	private function setDescripcion(string $descripcion): void {
		$this->descripcion = $descripcion;
	}
	public function getObservaciones(): string {
		return $this->observaciones;
	}
	private function setObservaciones(string $observaciones): void {
		$this->observaciones = $observaciones;
	}
	public function getMostrarObsPedidos(): bool {
		return $this->mostrar_obs_pedidos;
	}
	private function setMostrarObsPedidos(bool $mostrar_obs_pedidos): void {
		$this->mostrar_obs_pedidos = $mostrar_obs_pedidos;
	}
	public function getMostrarObsVentas(): bool {
		return $this->mostrar_obs_ventas;
	}
	private function setMostrarObsVentas(bool $mostrar_obs_ventas): void {
		$this->mostrar_obs_ventas = $mostrar_obs_ventas;
	}
	public function getCodigosBarras(): array {
		return $this->codigos_barras;
	}
	private function setCodigosBarras(array $codigos_barras): void {
		$this->codigos_barras = $codigos_barras;
	}
	public function getFotosList(): array {
		return $this->fotos_list;
	}
	private function setFotosList(array $fotos_list): void {
		$this->fotos_list = $fotos_list;
	}
	public function getNombreStatus(): string {
		return $this->nombre_status;
	}
	private function setNombreStatus(string $nombre_status): void {
		$this->nombre_status = $nombre_status;
	}

	public function isValid(): bool {
		return (
			!is_null($this->getNombre()) &&
			!is_null($this->getIdMarca()) &&
			!is_null($this->getIva()) &&
			!is_null($this->getPuc()) &&
			!is_null($this->getPvp()) &&
			!is_null($this->getStock())
		);
	}

	public function load(ORequest $req): void {
		$this->setId( $req->getParamInt('id') );
		$this->setLocalizador( $req->getParamInt('localizador') );
		$this->setNombre( $req->getParamString('nombre') );
		$this->setIdCategoria( $req->getParamInt('idCategoria') );
		$this->setIdMarca( $req->getParamInt('idMarca') );
		$this->setIdProveedor( $req->getParamInt('idProveedor') );
		$this->setReferencia( $req->getParamString('referencia') );
		$this->setPalb( $req->getParamFloat('palb') );
		$this->setPuc( $req->getParamFloat('puc') );
		$this->setPvp( $req->getParamFloat('pvp') );
		$this->setIva( $req->getParamInt('iva') );
		$this->setRe( $req->getParamFloat('re') );
		$this->setMargen( $req->getParamFloat('margen') );
		$this->setStock( $req->getParamInt('stock') );
		$this->setStockMin( $req->getParamInt('stockMin') );
		$this->setStockMax( $req->getParamInt('stockMax') );
		$this->setLoteOptimo( $req->getParamInt('loteOptimo') );
		$this->setVentaOnline( $req->getParamBool('ventaOnline') );
		$this->setFechaCaducidad( $req->getParamString('fechaCaducidad') );
		$this->setMostrarEnWeb( $req->getParamBool('mostrarEnWeb') );
		$this->setDescCorta( $req->getParamString('descCorta') );
		$this->setDescripcion( $req->getParamString('descripcion') );
		$this->setObservaciones( $req->getParamString('observaciones') );
		$this->setMostrarObsPedidos( $req->getParamBool('mostrarObsPedidos') );
		$this->setMostrarObsVentas( $req->getParamBool('mostrarObsVentas') );
		$this->setCodigosBarras( $req->getParam('codigosBarras') );
		$this->setFotosList( $req->getParam('fotosList') );
		$this->setNombreStatus( $req->getParamString('nombreStatus') );
	}
}
