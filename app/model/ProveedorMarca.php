<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class ProveedorMarca extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'proveedor_marca';
		$model = [
			'id_proveedor' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'proveedor.id',
				'comment' => 'Id del proveedor'
			],
			'id_marca' => [
				'type'    => OModel::PK,
				'incr' => false,
				'ref' => 'marca.id',
				'comment' => 'Id de la marca'
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