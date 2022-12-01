<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class LineaPedido extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada línea de un pedido'
			],
			'id_pedido' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'pedido.id',
				'comment'  => 'Id del pedido al que pertenece la línea'
			],
			'id_articulo' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'articulo.id',
				'comment'  => 'Id del artículo recibido'
			],
			'unidades' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Número de unidades recibidas'
			],
			'palb' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => null,
				'comment' => 'Precio de albarán del artículo'
			],
			'pvp' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'PVP del artículo'
			],
			'margen' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'Porcentaje de margen del artículo'
			],
			'iva' => [
				'type'     => OModel::FLOAT,
				'nullable' => false,
				'default'  => null,
				'comment'  => 'IVA del artículo'
			],
			're' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => false,
				'comment' => 'RE del artículo'
			],
			'descuento' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => false,
				'comment' => 'Porcentaje de descuento del artículo'
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

		parent::load($model);
	}
}
