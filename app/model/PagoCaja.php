<?php declare(strict_types=1);
class PagoCaja extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'pago_caja';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único para cada pago de caja'
			],
			'concepto' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 250,
				'comment' => 'Concepto del pago'
			],
			'importe' => [
				'type'    => OCore::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Importe de dinero sacado de la caja para realizar el pago'
			],
			'created_at' => [
				'type'    => OCore::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OCore::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			]
		];

		parent::load($table_name, $model);
	}
}