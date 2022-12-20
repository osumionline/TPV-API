<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class PedidoDTO implements ODTO{
  private ?int $id = null;
  private ?int $id_proveedor = null;
  private ?bool $re = null;
  private ?bool $ue = null;
  private ?string $albaran_factura = null;
  private ?string $num_albaran_factura = null;
  private ?string $fecha_pago = null;
  private ?string $fecha_pedido = null;
  private ?array $lineas = null;
  private ?float $importe = null;
  private ?float $portes = null;
  private ?bool $faltas = null;
  private ?bool $recepcionado = null;
  private ?string $observaciones = null;
  private ?array $pdfs = null;
  private ?array $vista = null;

  public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
	public function getIdProveedor(): ?int {
		return $this->id_proveedor;
	}
	private function setIdProveedor(?int $id_proveedor): void {
		$this->id_proveedor = $id_proveedor;
	}
	public function getRe(): ?bool {
		return $this->re;
	}
	private function setRe(?bool $re): void {
		$this->re = $re;
	}
	public function getUe(): ?bool {
		return $this->ue;
	}
	private function setUe(?bool $ue): void {
		$this->ue = $ue;
	}
	public function getAlbaranFactura(): ?string {
		return $this->albaran_factura;
	}
	private function setAlbaranFactura(?string $albaran_factura): void {
		$this->albaran_factura = $albaran_factura;
	}
	public function getNumAlbaranFactura(): ?string {
		return $this->num_albaran_factura;
	}
	private function setNumAlbaranFactura(?string $num_albaran_factura): void {
		$this->num_albaran_factura = $num_albaran_factura;
	}
	public function getFechaPago(): ?string {
		return $this->fecha_pago;
	}
	private function setFechaPago(?string $fecha_pago): void {
		$this->fecha_pago = $fecha_pago;
	}
	public function getFechaPedido(): ?string {
		return $this->fecha_pedido;
	}
	private function setFechaPedido(?string $fecha_pedido): void {
		$this->fecha_pedido = $fecha_pedido;
	}
	public function getLineas(): ?array {
		return $this->lineas;
	}
	private function setLineas(?array $lineas): void {
		$this->lineas = $lineas;
	}
	public function getImporte(): ?float {
		return $this->importe;
	}
	private function setImporte(?float $importe): void {
		$this->importe = $importe;
	}
	public function getPortes(): ?float {
		return $this->portes;
	}
	private function setPortes(?float $portes): void {
		$this->portes = $portes;
	}
	public function getFaltas(): ?bool {
		return $this->faltas;
	}
	private function setFaltas(?bool $faltas): void {
		$this->faltas = $faltas;
	}
	public function getRecepcionado(): ?bool {
		return $this->recepcionado;
	}
	private function setRecepcionado(?bool $recepcionado): void {
		$this->recepcionado = $recepcionado;
	}
  public function getObservaciones(): ?string {
		return $this->observaciones;
	}
	private function setObservaciones(?string $observaciones): void {
		$this->observaciones = $observaciones;
	}
	public function getPdfs(): ?array {
		return $this->pdfs;
	}
	private function setPdfs(?array $pdfs): void {
		$this->pdfs = $pdfs;
	}
  public function getVista(): ?array {
		return $this->vista;
	}
	private function setVista(?array $vista): void {
		$this->vista = $vista;
	}

	public function isValid(): bool {
		return (
			!is_null($this->getAlbaranFactura()) &&
			!is_null($this->getNumAlbaranFactura()) &&
			!is_null($this->getFechaPago()) &&
			!is_null($this->getFechaPedido()) &&
			!is_null($this->getLineas()) &&
			!is_null($this->getRecepcionado())
	    );
	}

	public function load(ORequest $req): void {
		$this->setId( $req->getParamInt('id') );
		$this->setIdProveedor( $req->getParamInt('idProveedor') );
		$this->setRe( $req->getParamBool('re') );
		$this->setUe( $req->getParamBool('ue') );
		$this->setAlbaranFactura( $req->getParamString('albaranFactura') );
		$this->setNumAlbaranFactura( $req->getParamString('numAlbaranFactura') );
		$this->setFechaPago( $req->getParamString('fechaPago') );
		$this->setFechaPedido( $req->getParamString('fechaPedido') );
		$this->setLineas( $req->getParam('lineas') );
		$this->setImporte( $req->getParamFloat('importe') );
		$this->setPortes( $req->getParamFloat('portes') );
		$this->setFaltas( $req->getParamBool('faltas') );
		$this->setRecepcionado( $req->getParamBool('recepcionado') );
    $this->setObservaciones( $req->getParamString('observaciones') );
		$this->setPdfs( $req->getParam('pdfs') );
    $this->setVista( $req->getParam('vista') );
	}
}
