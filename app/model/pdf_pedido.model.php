<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class PdfPedido extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada PDF'
			],
			'id_pedido' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => null,
				'ref'      => 'pedido.id',
				'comment'  => 'Id del pedido al que pertenece el PDF'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 200,
				'comment' => 'Nombre del archivo PDF'
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