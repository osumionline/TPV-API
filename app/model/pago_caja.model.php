<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class PagoCaja extends OModel {
	function __construct() {
	$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada pago de caja'
			],
			'concepto' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 250,
				'comment' => 'Concepto del pago'
			],
			'importe' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Importe de dinero sacado de la caja para realizar el pago'
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
