<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Cliente extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'cliente';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada cliente'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del cliente'
			],
			'apellidos' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Apellidos del cliente'
			],
			'dni_cif' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'DNI/CIF del cliente'
			],
			'telefono' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono del cliente'
			],
			'email' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Email del cliente'
			],
			'direccion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección del cliente'
			],
			'codigo_postal' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'Código postal del cliente'
			],
			'poblacion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 50,
				'comment' => 'Población del cliente'
			],
			'provincia' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Id de la provincia del cliente'
			],
			'observaciones' => [
				'type'    => OModel::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Campo libre para observaciones personales del cliente'
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