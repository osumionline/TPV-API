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
				'default' => '0',
				'comment' => 'Precio Unitario de Compra del artículo en el momento de su venta'
			],
			'pvp' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Precio de Venta al Público del artículo en el momento de su venta'
			],
			'iva' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'IVA del artículo en el momento de su venta'
			],
			'tipo_descuento' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Tipo de descuento 0 ninguno 1 porcentaje 2 importe'
			],
			'descuento' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Porcentaje de descuento aplicado'
			],
			'cantidad' => [
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
}