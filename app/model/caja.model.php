<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Caja extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada cierre de caja'
			],
			'apertura' => [
				'type'    => OModel::DATE,
				'nullable' => false,
				'default' => null,
				'comment' => 'Fecha de apertura de la caja'
			],
			'cierre' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de cierre de la caja'
			],
			'ventas' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total de ventas para el período de la caja'
			],
			'beneficios' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total de beneficios para el período de la caja'
			],
			'venta_efectivo' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total vendido en efectivo'
			],
			'venta_otros' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total vendido mediante tipos de pago alternativos'
			],
			'importe_apertura' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total en efectivo en la caja al momento de la apertura'
			],
			'importe_cierre' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total en efectivo en la caja al momento del cierre'
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
