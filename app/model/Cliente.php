<?php declare(strict_types=1);
class Cliente extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'cliente';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único de cada cliente'
			],
			'nombre' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del cliente'
			],
			'apellidos' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Apellidos del cliente'
			],
			'dni_cif' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'DNI/CIF del cliente'
			],
			'telefono' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono del cliente'
			],
			'email' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Email del cliente'
			],
			'direccion' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección del cliente'
			],
			'codigo_postal' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'Código postal del cliente'
			],
			'poblacion' => [
				'type'    => OCore::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 50,
				'comment' => 'Población del cliente'
			],
			'provincia' => [
				'type'    => OCore::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Id de la provincia del cliente'
			],
			'observaciones' => [
				'type'    => OCore::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Campo libre para observaciones personales del cliente'
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