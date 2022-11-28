<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class FacturaVenta extends OModel {
	function __construct() {
		$model = [
			'id_factura' => [
				'type'    => OModel::PK,
				'ref' => 'factura.id',
				'incr' => false,
				'comment' => 'Id de la factura'
			],
			'id_venta' => [
				'type'    => OModel::PK,
				'nullable' => false,
				'incr' => false,
				'ref' => 'venta.id',
				'comment' => 'Id de la venta'
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
