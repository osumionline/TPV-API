<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Pedido;

class HistoricoArticulo extends OModel {
	#[OPK(
	  comment: 'Id único para cada entrada del histórico'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del artículo',
	  nullable: false,
	  ref: 'articulo.id'
	)]
	public ?int $id_articulo;

	#[OField(
	  comment: 'Tipo de cambio 0 venta 1 pedido 2 manual',
	  nullable: false
	)]
	public ?int $tipo;

	#[OField(
	  comment: 'Stock previo al cambio',
	  nullable: false,
	  default: 0
	)]
	public ?int $stock_previo;

	#[OField(
	  comment: 'Incremento o reducción del stock',
	  nullable: false,
	  default: 0
	)]
	public ?int $diferencia;

	#[OField(
	  comment: 'Stock fiinal tras el cambio',
	  nullable: false,
	  default: 0
	)]
	public ?int $stock_final;

	#[OField(
	  comment: 'Id de la venta',
	  nullable: true,
	  ref: 'venta.id',
	  default: null
	)]
	public ?int $id_venta;

	#[OField(
	  comment: 'Id de la venta',
	  nullable: true,
	  ref: 'pedido.id',
	  default: null
	)]
	public ?int $id_pedido;

	#[OField(
	  comment: 'PUC del artículo enn el momento del cambio',
	  nullable: false,
	  default: 0
	)]
	public ?float $puc;

	#[OField(
	  comment: 'PVP del artículo enn el momento del cambio',
	  nullable: false,
	  default: 0
	)]
	public ?float $pvp;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	const FROM_VENTA          = 1;
	const FROM_VENTA_SYNC     = 2;
	const FROM_PEDIDO         = 3;
	const FROM_ARTICULO       = 4;
	const FROM_INVENTARIO     = 5;
	const FROM_INVENTARIO_ALL = 6;

	private ?Articulo $articulo = null;

	/**
	 * Obtiene el artículo al que pertenece el registro
	 *
	 * @return Articulo Artículo al que pertenece el registro
	 */
	public function getArticulo(): Articulo {
		if (is_null($this->articulo)) {
			$this->loadArticulo();
		}
		return $this->articulo;
	}

	/**
	 * Guarda el artículo al que pertenece el registro
	 *
	 * @param Articulo $a Artículo al que pertenece el registro
	 *
	 * @return void
	 */
	public function setArticulo(Articulo $a): void {
		$this->articulo = $a;
	}

	/**
	 * Carga el artículo al que pertenece el registro
	 *
	 * @return void
	 */
	public function loadArticulo(): void {
		$this->setArticulo(Articulo::findOne(['id' => $this->id_articulo]));
	}

	private ?Venta $venta = null;

	/**
	 * Obtiene la venta a la que pertenece el registro
	 *
	 * @return Venta Venta a la que pertenece el registro
	 */
	public function getVenta(): Venta {
		if (is_null($this->venta)) {
			$this->loadVenta();
		}
		return $this->venta;
	}

	/**
	 * Guarda la venta a la que pertenece el registro
	 *
	 * @param Venta $v Venta a la que pertenece el registro
	 *
	 * @return void
	 */
	public function setVenta(Venta $v): void {
		$this->venta = $v;
	}

	/**
	 * Carga la venta a la que pertenece el registro
	 *
	 * @return void
	 */
	public function loadVenta(): void {
		$this->setVenta(Venta::findOne(['id' => $this->id_venta]));
	}

	private ?Pedido $pedido = null;

	/**
	 * Obtiene el pedido al que pertenece el registro
	 *
	 * @return Pedido Pedido al que pertenece el registro
	 */
	public function getPedido(): Pedido {
		if (is_null($this->pedido)) {
			$this->loadPedido();
		}
		return $this->pedido;
	}

	/**
	 * Guarda el pedido al que pertenece el registro
	 *
	 * @param Pedido $p Pedido al que pertenece el registro
	 *
	 * @return void
	 */
	public function setPedido(Pedido $p): void {
		$this->pedido = $p;
	}

	/**
	 * Carga el pedido al que pertenece el registro
	 *
	 * @return void
	 */
	public function loadPedido(): void {
		$this->setPedido(Pedido::findOne(['id' => $this->id_pedido]));
	}
}
