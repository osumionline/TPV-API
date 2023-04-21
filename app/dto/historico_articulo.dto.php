<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class HistoricoArticuloDTO implements ODTO {
  private ?int $id = null;
  private ?string $order_by = null;
  private ?string $order_sent = null;
  private ?int $pagina = null;
  private ?int $num = null;

  public function getId(): ?int {
		return $this->id;
	}
	private function setId(?int $id): void {
		$this->id = $id;
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
		return (
      !is_null($this->getId()) &&
      !is_null($this->getPagina()) &&
      !is_null($this->getNum())
    );
	}

  public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
    $this->setOrderBy( $req->getParamString('orderBy') );
    $this->setOrderSent( $req->getParamString('orderSent') );
    $this->setPagina( $req->getParamInt('pagina') );
    $this->setNum( $req->getParamInt('num') );
  }
}
