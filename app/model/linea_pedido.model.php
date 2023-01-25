<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class LineaPedido extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada línea de un pedido'
			),
			new OModelField(
				name: 'id_pedido',
				type: OMODEL_NUM,
				nullable: false,
				ref: 'pedido.id',
				comment: 'Id del pedido al que pertenece la línea'
			),
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_NUM,
				nullable: false,
				ref: 'articulo.id',
				comment: 'Id del artículo recibido'
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
				name: 'codigo_barras',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Nuevo código de barras para el artículo'
			),
			new OModelField(
				name: 'unidades',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Número de unidades recibidas'
			),
			new OModelField(
				name: 'palb',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Precio de albarán del artículo'
			),
			new OModelField(
				name: 'pvp',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'PVP del artículo'
			),
			new OModelField(
				name: 'margen',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Porcentaje de margen del artículo'
			),
			new OModelField(
				name: 'iva',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'IVA del artículo'
			),
			new OModelField(
				name: 're',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'RE del artículo'
			),
			new OModelField(
				name: 'descuento',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Porcentaje de descuento del artículo'
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

	private ?Articulo $articulo = null;

	/**
	 * Obtiene el artículo al que pertenece la línea
	 *
	 * @return Articulo Artículo al que pertenece la línea
	 */
	public function getArticulo(): Articulo {
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
