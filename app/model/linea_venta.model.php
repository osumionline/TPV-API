<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class LineaVenta extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id única de cada línea de venta'
			),
			new OModelField(
				name: 'id_venta',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'venta.id',
				comment: 'Id de la venta a la que pertenece la línea'
			),
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'articulo.id',
				comment: 'Id del artículo que está siendo vendido'
			),
			new OModelField(
				name: 'nombre_articulo',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Nombre del artículo'
			),
			new OModelField(
				name: 'puc',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Precio Unitario de Compra del artículo en el momento de su venta'
			),
			new OModelField(
				name: 'pvp',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Precio de Venta al Público del artículo en el momento de su venta'
			),
			new OModelField(
				name: 'iva',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'IVA del artículo en el momento de su venta'
			),
			new OModelField(
				name: 'importe',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total de la línea'
			),
			new OModelField(
				name: 'descuento',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Porcentaje de descuento aplicado'
			),
			new OModelField(
				name: 'importe_descuento',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Importe directo en descuento'
			),
			new OModelField(
				name: 'devuelto',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Cantidad de artículos devueltos'
			),
			new OModelField(
				name: 'unidades',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Cantidad de artículos vendidos'
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

	/**
	 * Obtiene el beneficio de la venta
	 *
	 * @return float Beneficio de la venta
	 */
	public function getBeneficio(): float {
		return $this->get('unidades') * ($this->get('importe') - $this->get('puc'));
	}

	private ?Venta $venta = null;

	/**
	 * Obtiene la venta a la que pertenece la línea
	 *
	 * @return Venta Venta a la que pertenece la línea
	 */
	public function getVenta(): Venta {
		if (is_null($this->venta)) {
			$this->loadVenta();
		}
		return $this->venta;
	}

	/**
	 * Guarda la venta a la que pertenece la línea
	 *
	 * @param Venta $v Venta a la que pertenece la línea
	 *
	 * @return void
	 */
	public function setVenta(Venta $v): void {
		$this->venta = $v;
	}

	/**
	 * Carga la venta a la que pertenece la línea
	 *
	 * @return void
	 */
	public function loadVenta(): void {
		$v = new Venta();
		$v->find(['id' => $this->get('id_venta')]);
		$this->setVenta($v);
	}

	private ?Articulo $articulo = null;

	/**
	 * Obtiene el artículo al que pertenece la línea
	 *
	 * @return Articulo Artículo al que pertenece la línea
	 */
	public function getArticulo(): ?Articulo {
		if (is_null($this->get('id_articulo'))) {
			return null;
		}
		if (is_null($this->articulo)) {
			$this->loadArticulo();
		}
		return $this->articulo;
	}

	/**
	 * Guarda el artículo al que pertenece la línea
	 *
	 * @param Articulo $a Artículo al que pertenece la línea
	 *
	 * @return void
	 */
	public function setArticulo(Articulo $a): void {
		$this->articulo = $a;
	}

	/**
	 * Carga el artículo al que pertenece la línea
	 *
	 * @return void
	 */
	public function loadArticulo(): void {
		$a = new Articulo();
		$a->find(['id' => $this->get('id_articulo')]);
		$this->setArticulo($a);
	}
}
