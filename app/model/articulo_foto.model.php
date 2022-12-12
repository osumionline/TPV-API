<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class ArticuloFoto extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id_foto',
				type: OMODEL_PK,
				ref: 'foto.id',
				incr: false,
				comment: 'Id único para cada foto'
			),
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_PK,
				nullable: false,
				incr: false,
				ref: 'articulo.id',
				comment: 'Id del artículo al que pertenece la foto'
			),
			new OModelField(
				name: 'orden',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Orden de la foto entre todas las fotos de un artículo'
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
