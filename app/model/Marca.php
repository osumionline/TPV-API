<?php declare(strict_types=1);
class Marca extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'marca';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único para cada marca'
			],
			'nombre' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre de la marca'
			],
			'telefono' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono de la marca'
			],
			'email' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de email de la marca'
			],
			'web' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de la página web de la marca'
			],
			'observaciones' => [
				'type'    => OCore::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Observaciones o notas personales de la marca'
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