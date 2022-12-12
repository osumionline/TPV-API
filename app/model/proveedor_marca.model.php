<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class ProveedorMarca extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_proveedor',
				type: OMODEL_PK,
				incr: false,
				ref: 'proveedor.id',
				comment: 'Id del proveedor'
			),
			new OModelField(
				name: 'id_marca',
				type: OMODEL_PK,
				incr: false,
				ref: 'marca.id',
				comment: 'Id de la marca'
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
