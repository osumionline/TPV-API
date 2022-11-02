<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class EmpleadoRol extends OModel {
	function __construct() {
		$model = [
			'id_empleado' => [
				'type'    => OModel::PK,
				'ref' => 'empleado.id',
				'incr' => false,
				'comment' => 'Id del empleado'
			],
			'id_rol' => [
				'type'    => OModel::PK,
				'nullable' => false,
				'incr' => false,
				'comment' => 'Id del permiso que se le otorga al empleado'
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

		parent::load($model);
	}
}
