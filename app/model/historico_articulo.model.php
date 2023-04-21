<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class HistoricoArticulo extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada entrada del histórico'
			),
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_NUM,
				nullable: false,
				ref: 'articulo.id',
				comment: 'Id del artículo'
			),
			new OModelField(
				name: 'tipo',
				type: OMODEL_NUM,
				nullable: false,
				comment: 'Tipo de cambio 0 venta 1 pedido 2 manual'
			),
			new OModelField(
				name: 'stock_previo',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock previo al cambio'
			),
			new OModelField(
				name: 'diferencia',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Incremento o reducción del stock'
			),
			new OModelField(
				name: 'stock_final',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock fiinal tras el cambio'
			),
			new OModelField(
				name: 'id_venta',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'venta.id',
				comment: 'Id de la venta'
			),
			new OModelField(
				name: 'id_pedido',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'pedido.id',
				comment: 'Id de la venta'
			),
			new OModelField(
				name: 'puc',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'PUC del artículo enn el momento del cambio'
			),
			new OModelField(
				name: 'pvp',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'PVP del artículo enn el momento del cambio'
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
