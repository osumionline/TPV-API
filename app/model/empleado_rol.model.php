<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class EmpleadoRol extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_empleado',
				type: OMODEL_PK,
				ref: 'empleado.id',
				incr: false,
				comment: 'Id del empleado'
			),
			new OModelField(
				name: 'id_rol',
				type: OMODEL_PK,
				nullable: false,
				incr: false,
				comment: 'Id del permiso que se le otorga al empleado'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			)
		);


		parent::load($model);
	}
}
