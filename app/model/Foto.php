<?php declare(strict_types=1);
class Foto extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'foto';
		$model = [
			'id' => [
				'type'    => OCore::PK,
				'comment' => 'Id único para cada foto'
			],
			'id_articulo' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'articulo.id',
				'comment' => 'Id del artículo al que pertenece la foto'
			],
			'ext' => [
				'type'    => OCore::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 5,
				'comment' => 'Extensión del archivo de la foto'
			],
			'orden' => [
				'type'    => OCore::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Orden de la foto entre todas las fotos de un artículo'
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