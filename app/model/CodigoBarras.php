<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class CodigoBarras extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'codigo_barras';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada código de barras'
			],
			'id_articulo' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'articulo.id',
				'comment' => 'Id del artículo al que pertenece el código de barras'
			],
			'codigo_barras' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'comment' => 'Código de barras del artículo'
			],
			'por_defecto' => [
				'type'    => OModel::BOOL,
				'comment' => 'Indica si es el código de barras asignado por defecto por el TPV 1 o añadido a mano 1'
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