<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Caja extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'caja';
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
			'diferencia' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Diferencia entre el importe que debería haber y el que realmente hay'
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
			'venta_tarjetas' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total vendido mediante tarjetas'
			],
			'efectivo_apertura' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total en efectivo en la caja al momento de la apertura'
			],
			'efectivo_cierre' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Importe total en efectivo en la caja al momento del cierre'
			],
			'1c' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de un centimo'
			],
			'2c' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de dos centimos'
			],
			'5c' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de cinco centimos'
			],
			'10c' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de diez centimos'
			],
			'20c' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de veinte centimos'
			],
			'50c' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de cincuenta centimos'
			],
			'1e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de un euro'
			],
			'2e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de monedas de dos euros'
			],
			'5e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de cinco euros'
			],
			'10e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de diez euros'
			],
			'20e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de veinte euros'
			],
			'50e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de cincuenta euros'
			],
			'100e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de cien euros'
			],
			'200e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de doscientos euros'
			],
			'500e' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => '0',
				'comment' => 'Cantidad de billetes de quinientos euros'
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