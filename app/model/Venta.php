<?php declare(strict_types=1);
class Venta extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'venta';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único de cada venta'
			],
			'id_cliente' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'cliente.id',
				'comment' => 'Id del cliente'
			],
			'total' => [
				'type'    => OCore::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Importe total de la venta'
			],
			'entregado' => [
				'type'    => OCore::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Importe entregado por el cliente'
			],
			'tipo_pago' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Tipo de pago 0 metálico 1 tarjeta 2 web'
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