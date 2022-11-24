<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class VentaDTO implements ODTO{
	private ?float $efectivo = null;
	private ?float $cambio = null;
	private ?float $tarjeta = null;
	private ?int $id_empleado = null;
	private ?int $id_tipo_pago = null;
	private ?int $id_cliente = null;
	private ?float $total = null;
	private array $lineas = [];
	private bool $pago_mixto = false;
	private bool $factura = false;
	private bool $regalo = false;

	public function getEfectivo(): ?float {
		return $this->efectivo;
	}
	private function setEfectivo(?float $efectivo): void {
		$this->efectivo = $efectivo;
	}
	public function getCambio(): ?float {
		return $this->cambio;
	}
	private function setCambio(?float $cambio): void {
		$this->cambio = $cambio;
	}
	public function getTarjeta(): ?float {
		return $this->tarjeta;
	}
	private function setTarjeta(?float $tarjeta): void {
		$this->tarjeta = $tarjeta;
	}
	public function getIdEmpleado(): ?int {
		return $this->id_empleado;
	}
	private function setIdEmpleado(?int $id_empleado): void {
		$this->id_empleado = $id_empleado;
	}
	public function getIdTipoPago(): ?int {
		return $this->id_tipo_pago;
	}
	private function setIdTipoPago(?int $id_tipo_pago): void {
		$this->id_tipo_pago = $id_tipo_pago;
	}
	public function getIdCliente(): ?int {
		return $this->id_cliente;
	}
	private function setIdCliente(?int $id_cliente): void {
		$this->id_cliente = $id_cliente;
	}
	public function getTotal(): ?float {
		return $this->total;
	}
	private function setTotal(?float $total): void {
		$this->total = $total;
	}
	public function getLineas(): array {
		return $this->lineas;
	}
	private function setLineas(array $lineas): void {
		$this->lineas = $lineas;
	}
	public function getPagoMixto(): bool {
		return $this->pago_mixto;
	}
	private function setPagoMixto(bool $pago_mixto): void {
		$this->pago_mixto = $pago_mixto;
	}
	public function getFactura(): bool {
		return $this->factura;
	}
	private function setFactura(bool $factura): void {
		$this->factura = $factura;
	}
	public function getRegalo(): bool {
		return $this->regalo;
	}
	private function setRegalo(bool $regalo): void {
		$this->regalo = $regalo;
	}

	public function isValid(): bool {
		return (count($this->getLineas()) > 0);
	}

	public function load(ORequest $req): void {
		$this->setEfectivo( $req->getParamFloat('efectivo') );
		$this->setCambio( $req->getParamFloat('cambio') );
		$this->setTarjeta( $req->getParamFloat('tarjeta') );
		$this->setIdEmpleado( $req->getParamInt('idEmpleado') );
		$this->setIdTipoPago( $req->getParamInt('idTipoPago') );
		$this->setIdCliente( $req->getParamInt('idCliente') );
		$this->setTotal( $req->getParamFloat('total') );
		$this->setLineas( $req->getParam('lineas') );
		$this->setPagoMixto( $req->getParamBool('pagoMixto') );
		$this->setFactura( $req->getParamBool('factura') );
		$this->setRegalo( $req->getParamBool('regalo') );
	}
}
