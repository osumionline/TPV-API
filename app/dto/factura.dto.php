<?php declare(strict_types=1);

namespace OsumiFramework\App\DTO;

use OsumiFramework\OFW\Core\ODTO;
use OsumiFramework\OFW\Web\ORequest;

class FacturaDTO implements ODTO {
  private ?int $id = null;
  private ?int $id_cliente = null;
  private ?array $ventas = null;

  public function getId(): ?int {
		return $this->id;
	}
	public function setId(?int $id): void {
		$this->id = $id;
	}
  public function getIdCliente(): ?int {
		return $this->id_cliente;
	}
	private function setIdCliente(?int $id_cliente): void {
		$this->id_cliente = $id_cliente;
	}
  public function getVentas(): ?array {
		return $this->ventas;
	}
	private function setVentas(?array $ventas): void {
		$this->ventas = $ventas;
	}

  public function isValid(): bool {
		return (
      !is_null($this->getIdCliente()) &&
      !is_null($this->getVentas()) &&
      is_array($this->getVentas()) &&
      count($this->getVentas()) > 0
    );
	}

  public function load(ORequest $req): void {
    $this->setId( $req->getParamInt('id') );
  	$this->setIdCliente( $req->getParamInt('idCliente') );
    $this->setVentas( $req->getParam('ventas') );
  }
}
