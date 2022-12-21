<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class ArticuloEtiqueta extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_PK,
				nullable: false,
				incr: false,
				ref: 'articulo.id',
				comment: 'Id del artículo'
			),
			new OModelField(
				name: 'id_etiqueta',
				type: OMODEL_PK,
				nullable: false,
				incr: false,
				ref: 'etiqueta.id',
				comment: 'Id de la etiqueta'
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
