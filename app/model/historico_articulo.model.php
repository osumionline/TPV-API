<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class HistoricoArticulo extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada entrada del histórico'
			),
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_NUM,
				nullable: false,
				ref: 'articulo.id',
				comment: 'Id del artículo'
			),
			new OModelField(
				name: 'tipo',
				type: OMODEL_NUM,
				nullable: false,
				comment: 'Tipo de cambio 0 venta 1 pedido 2 manual'
			),
			new OModelField(
				name: 'stock_previo',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock previo al cambio'
			),
			new OModelField(
				name: 'diferencia',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Incremento o reducción del stock'
			),
			new OModelField(
				name: 'stock_final',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock fiinal tras el cambio'
			),
			new OModelField(
				name: 'id_venta',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'venta.id',
				comment: 'Id de la venta'
			),
			new OModelField(
				name: 'id_pedido',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'pedido.id',
				comment: 'Id de la venta'
			),
			new OModelField(
				name: 'puc',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'PUC del artículo enn el momento del cambio'
			),
			new OModelField(
				name: 'pvp',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'PVP del artículo enn el momento del cambio'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			)
		);

		parent::load($model);
	}

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
		$a = new Articulo();
		$a->find(['id' => $this->get('id_articulo')]);
		$this->setArticulo($a);
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
		$v = new Venta();
		$v->find(['id' => $this->get('id_venta')]);
		$this->setVenta($v);
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
		$p = new Pedido();
		$p->find(['id' => $this->get('id_pedido')]);
		$this->setPedido($p);
	}
}
