<?php declare(strict_types=1);
class ProveedorMarca extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'proveedor_marca';
		$model = [
			'id_proveedor' => [
				'type'    => OCore::PK,
				'incr' => false,
				'ref' => 'proveedor.id',
				'comment' => 'Id del proveedor'
			],
			'id_marca' => [
				'type'    => OCore::PK,
				'incr' => false,
				'ref' => 'marca.id',
				'comment' => 'Id de la marca'
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