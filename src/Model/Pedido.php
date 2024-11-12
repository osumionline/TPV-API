<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Proveedor;
use Osumi\OsumiFramework\App\Model\LineaPedido;
use Osumi\OsumiFramework\App\Model\PdfPedido;
use Osumi\OsumiFramework\App\Model\VistaPedido;

class Pedido extends OModel {
	#[OPK(
	  comment: 'Id único para cada pedido'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del proveedor del pedido',
	  nullable: true,
	  ref: 'proveedor.id',
	  default: null
	)]
	public ?int $id_proveedor;

	#[OField(
	  comment: 'Método de pago del pedido',
	  nullable: true,
	  default: null
	)]
	public ?int $metodo_pago;

	#[OField(
	  comment: 'Indica si se trata de un albarán, una factura o un abono',
	  nullable: true,
	  max: 10
	)]
	public ?string $tipo;

	#[OField(
	  comment: 'Albarán / factura / abono del pedido',
	  nullable: true,
	  max: 200,
	  default: null
	)]
	public ?string $num;

	#[OField(
	  comment: 'Importe total del pedido',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe;

	#[OField(
	  comment: 'Importe de los portes del pedido',
	  nullable: false,
	  default: 0
	)]
	public ?float $portes;

	#[OField(
	  comment: 'Porcentaje de descuento en el pedido',
	  nullable: false,
	  default: 0
	)]
	public ?int $descuento;

	#[OField(
	  comment: 'Fecha de pago del pedido',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $fecha_pago;

	#[OField(
	  comment: 'Fecha del pedido',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $fecha_pedido;

	#[OField(
	  comment: 'Fecha del momento de la recepcion del pedido',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $fecha_recepcionado;

	#[OField(
	  comment: 'Indica si el pedido tiene RE 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $re;

	#[OField(
	  comment: 'Indica si se rata de un pedido europeo 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $europeo;

	#[OField(
	  comment: 'Indica si hay faltas en el pedido 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $faltas;

	#[OField(
	  comment: 'Indica si se ha recepcionado el pedido 1 o si está pendiente 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $recepcionado;

	#[OField(
	  comment: 'Observaciones o notas sobre el pedido',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $observaciones;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Función para obtener el nombre del método de pago
	 *
	 * @return string Nombre del método de pago
	 */
	public function getMetodoPago(): ?string {
		if (is_null($this->metodo_pago)) {
			return null;
		}
		switch ($this->metodo_pago) {
			case 0: {
				return 'Domiciliación bancaria';
			}
			break;
			case 1: {
				return 'Tarjeta';
			}
			break;
			case 2: {
				return 'Paypal';
			}
			break;
			case 3: {
				return 'Al contado';
			}
			break;
			case 4: {
				return 'Transferencia bancaria';
			}
			break;
		}
		return null;
	}

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece el pedido
	 *
	 * @return Proveedor Proveedor al que pertenece el pedido, a no ser que se haya borrado
	 */
	public function getProveedor(): ?Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		if (!is_null($this->proveedor->deleted_at)){
			return null;
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece el pedido
	 *
	 * @param Proveedor $p Proveedor al que pertenece el pedido
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece el pedido
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$this->setProveedor(Proveedor::findOne(['id' => $this->id_proveedor]));
	}

	private ?array $lineas = null;

	/**
	 * Obtiene el listado de líneas de un pedido
	 *
	 * @return array Listado de líneas
	 */
	public function getLineas(): array {
		if (is_null($this->lineas)) {
			$this->loadLineas();
		}
		return $this->lineas;
	}

	/**
	 * Guarda la lista de líneas
	 *
	 * @param array $l Lista de líneas
	 *
	 * @return void
	 */
	public function setLineas(array $l): void {
		$this->lineas = $l;
	}

	/**
	 * Carga la lista de líneas de un pedido
	 *
	 * @return void
	 */
	public function loadLineas(): void {
		$this->setLineas(LineaPedido::where(['id_pedido' => $this->id]));
	}

	private ?array $pdfs = null;

	/**
	 * Obtiene el listado de PDFs de un pedido
	 *
	 * @return array Listado de PDFs
	 */
	public function getPdfs(): array {
		if (is_null($this->pdfs)) {
			$this->loadPdfs();
		}
		return $this->pdfs;
	}

	/**
	 * Guarda la lista de PDFs
	 *
	 * @param array $l Lista de PDFs
	 *
	 * @return void
	 */
	public function setPdfs(array $l): void {
		$this->pdfs = $l;
	}

	/**
	 * Carga la lista de PDFs de un pedido
	 *
	 * @return void
	 */
	public function loadPdfs(): void {
		$this->setPdfs(PdfPedido::where(['id_pedido' => $this->id]));
	}

	private ?array $vista = null;

	/**
	 * Obtiene el listado de opciones para la vista de un pedido
	 *
	 * @return array Listado de opciones
	 */
	public function getVista(): array {
		if (is_null($this->vista)) {
			$this->loadVista();
		}
		return $this->vista;
	}

	/**
	 * Guarda la lista de opciones para la vista de un pedido
	 *
	 * @param array $l Lista de opciones
	 *
	 * @return void
	 */
	public function setVista(array $v): void {
		$this->vista = $v;
	}

	/**
	 * Carga la lista de opciones para la vista de un pedido
	 *
	 * @return void
	 */
	public function loadVista(): void {
		$this->setVista(VistaPedido::where(['id_pedido' => $this->id], ['order_by' => 'id_column#asc']));
	}

	/**
	 * Función para borrar un pedido, sus líneas, sus pdfs y su vista
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$lineas = $this->getLineas();
		foreach ($lineas as $linea) {
			$linea->delete();
		}

		$pdfs = $this->getPdfs();
		foreach ($pdfs as $pdf) {
			$pdf->deleteFull();
		}

		$vista = $this->getVista();
		foreach ($vista as $v) {
			$v->delete();
		}

		$this->delete();
	}
}
