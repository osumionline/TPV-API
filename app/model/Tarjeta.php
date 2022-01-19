<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Tarjeta extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'tarjeta';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada tarjeta'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre de la tarjeta'
			],
			'slug' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Slug del nombre de la tarjeta'
			],
			'comision' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Porcentaje de comision que se cobra en cada venta'
			],
			'por_defecto' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si es el tipo de tarjeta por defecto 1 o no 0'
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
