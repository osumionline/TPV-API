<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class PedidosFilterDTO implements ODTO {
  private ?string $fecha_desde = null;
  private ?string $fecha_hasta = null;
  private ?int $id_proveedor = null;
  private ?string $albaran = null;
  private ?float $importe_desde = null;
  private ?float $importe_hasta = null;
  private ?int $pagina = null;
  private ?int $num = null;

  public function getFechaDesde(): ?string {
		return $this->fecha_desde;
	}
	private function setFechaDesde(?string $fecha_desde): void {
		$this->fecha_desde = $fecha_desde;
	}
  public function getFechaHasta(): ?string {
		return $this->fecha_hasta;
	}
	private function setFechaHasta(?string $fecha_hasta): void {
		$this->fecha_hasta = $fecha_hasta;
	}
  public function getIdProveedor(): ?int {
		return $this->id_proveedor;
	}
	private function setIdProveedor(?int $id_proveedor): void {
		$this->id_proveedor = $id_proveedor;
	}
  public function getAlbaran(): ?string {
		return $this->albaran;
	}
	private function setAlbaran(?string $albaran): void {
		$this->albaran = $albaran;
	}
  public function getImporteDesde(): ?float {
		return $this->importe_desde;
	}
	private function setImporteDesde(?float $importe_desde): void {
		$this->importe_desde = $importe_desde;
	}
  public function getImporteHasta(): ?float {
		return $this->importe_hasta;
	}
	private function setImporteHasta(?float $importe_hasta): void {
		$this->importe_hasta = $importe_hasta;
	}
  public function getPagina(): ?int {
		return $this->pagina;
	}
	private function setPagina(?int $pagina): void {
		$this->pagina = $pagina;
	}
  public function getNum(): ?int {
		return $this->num;
	}
	private function setNum(?int $num): void {
		$this->num = $num;
	}

  public function isValid(): bool {
		return (
      !is_null($this->getPagina()) &&
      !is_null($this->getNum())
    );
	}

	public function load(ORequest $req): void {
    $this->setFechaDesde( $req->getParamString('fechaDesde') );
    $this->setFechaHasta( $req->getParamString('fechaHasta') );
    $this->setIdProveedor( $req->getParamInt('idProveedor') );
    $this->setAlbaran( $req->getParamString('albaran') );
    $this->setImporteDesde( $req->getParamFloat('importeDesde') );
    $this->setImporteHasta( $req->getParamFloat('importeHasta') );
    $this->setPagina( $req->getParamInt('pagina') );
    $this->setNum( $req->getParamInt('num') );
  }
}
