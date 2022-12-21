<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class EtiquetaWeb extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada etiqueta web'
			),
			new OModelField(
				name: 'texto',
				type: OMODEL_TEXT,
				nullable: false,
				size: 50,
				comment: 'Texto de la etiqueta web'
			),
			new OModelField(
				name: 'slug',
				type: OMODEL_TEXT,
				nullable: false,
				size: 50,
				comment: 'Slug del texto de la etiqueta web'
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
