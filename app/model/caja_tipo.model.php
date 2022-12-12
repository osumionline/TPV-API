<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class CajaTipo extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_caja',
				type: OMODEL_PK,
				ref: 'caja.id',
				incr: false,
				comment: 'Id de la caja del desglose'
			),
			new OModelField(
				name: 'id_tipo_pago',
				type: OMODEL_PK,
				ref: 'tipo_pago.id',
				incr: false,
				comment: 'Id del tipo de pago'
			),
			new OModelField(
				name: 'operaciones',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Numero de operaciones por tipo de pago'
			),
			new OModelField(
				name: 'importe_total',
				type: OMODEL_FLOAT,
				nullable: true,
				default: 0,
				comment: 'Importe del tipo de pago'
			),
			new OModelField(
				name: 'importe_real',
				type: OMODEL_FLOAT,
				nullable: true,
				default: 0,
				comment: 'Importe real del tipo de pago'
			),
			new OModelField(
				name: 'importe_descuento',
				type: OMODEL_FLOAT,
				nullable: true,
				default: 0,
				comment: 'Importe total de descuentos para un tipo de pago'
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
