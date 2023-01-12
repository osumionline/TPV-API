<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class InventarioDTO implements ODTO {
  private ?int $id_proveedor = null;
  private ?int $id_marca = null;
  private ?string $nombre = null;
  private ?string $order_by = null;
  private ?string $order_sent = null;
  private ?int $pagina = null;
  private ?int $num = null;

  public function getIdProveedor(): ?int {
		return $this->id_proveedor;
	}
	private function setIdProveedor(?int $id_proveedor): void {
		$this->id_proveedor = $id_proveedor;
	}
  public function getIdMarca(): ?int {
		return $this->id_marca;
	}
	private function setIdMarca(?int $id_marca): void {
		$this->id_marca = $id_marca;
	}
  public function getNombre(): ?string {
		return $this->nombre;
	}
	private function setNombre(?string $nombre): void {
		$this->nombre = $nombre;
	}
  public function getOrderBy(): ?string {
		return $this->order_by;
	}
	private function setOrderBy(?string $order_by): void {
		$this->order_by = $order_by;
	}
  public function getOrderSent(): ?string {
		return $this->order_sent;
	}
	private function setOrderSent(?string $order_sent): void {
		$this->order_sent = $order_sent;
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
	public function setNum(?int $num): void {
		$this->num = $num;
	}

  public function isValid(): bool {
		return (!is_null($this->getPagina()));
	}

  public function load(ORequest $req): void {
    $this->setIdProveedor( $req->getParamInt('idProveedor') );
  	$this->setIdMarca( $req->getParamInt('idMarca') );
    $this->setNombre( $req->getParamString('nombre') );
    $this->setOrderBy( $req->getParamString('orderBy') );
    $this->setOrderSent( $req->getParamString('orderSent') );
    $this->setPagina( $req->getParamInt('pagina') );
    $this->setNum( $req->getParamInt('num') );
  }
}
