<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class VistaPedido extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_pedido',
				type: OMODEL_PK,
				ref: 'pedido.id',
				incr: false,
				comment: 'Id del pedido'
			),
			new OModelField(
				name: 'id_column',
				type: OMODEL_PK,
				incr: false,
				nullable: false,
				comment: 'Id de la columna a mostrar/ocultar'
			),
      new OModelField(
				name: 'status',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si la columna se tiene que mostrar 1 o no 0'
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
