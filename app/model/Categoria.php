<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Categoria extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'categoria';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada categoría'
			],
			'id_padre' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Id de la categoría padre en caso de ser una subcategoría'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre de la categoría'
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