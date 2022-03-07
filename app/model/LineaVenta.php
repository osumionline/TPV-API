<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class LineaVenta extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'linea_venta';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id única de cada línea de venta'
			],
			'id_venta' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'venta.id',
				'comment' => 'Id de la venta a la que pertenece la línea'
			],
			'id_articulo' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'articulo.id',
				'comment' => 'Id del artículo que está siendo vendido'
			],
			'puc' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Precio Unitario de Compra del artículo en el momento de su venta'
			],
			'pvp' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Precio de Venta al Público del artículo en el momento de su venta'
			],
			'iva' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => 0,
				'comment' => 'IVA del artículo en el momento de su venta'
			],
			're' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Recargo de equivalencia del artículo en el momento de su venta'
			],
			'importe' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Importe total de la línea'
			],
			'descuento' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Porcentaje de descuento aplicado'
			],
			'importe_descuento' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => 0,
				'comment' => 'Importe directo en descuento'
			],
			'devuelto' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Cantidad de artículos devueltos'
			],
			'unidades' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Cantidad de artículos vendidos'
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
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
}
