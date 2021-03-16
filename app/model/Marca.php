<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Marca extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'marca';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada marca'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre de la marca'
			],
			'telefono' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono de la marca'
			],
			'email' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de email de la marca'
			],
			'web' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de la página web de la marca'
			],
			'observaciones' => [
				'type'    => OModel::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Observaciones o notas personales de la marca'
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