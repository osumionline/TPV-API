<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class ArticuloFoto extends OModel {
	function __construct() {
		$model = [
			'id_foto' => [
				'type'    => OModel::PK,
				'ref' => 'foto.id',
				'incr' => false,
				'comment' => 'Id único para cada foto'
			],
			'id_articulo' => [
				'type'    => OModel::PK,
				'nullable' => false,
				'incr' => false,
				'ref' => 'articulo.id',
				'comment' => 'Id del artículo al que pertenece la foto'
			],
			'orden' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Orden de la foto entre todas las fotos de un artículo'
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
