<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class FacturaVenta extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_factura',
				type: OMODEL_PK,
				ref: 'factura.id',
				incr: false,
				comment: 'Id de la factura'
			),
			new OModelField(
				name: 'id_venta',
				type: OMODEL_PK,
				nullable: false,
				incr: false,
				ref: 'venta.id',
				comment: 'Id de la venta'
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
