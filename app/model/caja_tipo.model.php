<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class CajaTipo extends OModel {
	function __construct() {
		$model = [
			'id_caja' => [
				'type'    => OModel::PK,
        		'ref'     => 'caja.id',
				'incr'    => false,
				'comment' => 'Id de la caja del desglose'
			],
			'id_tipo_pago' => [
        		'type'    => OModel::PK,
        		'ref'     => 'tipo_pago.id',
				'incr'    => false,
				'comment' => 'Id del tipo de pago'
			],
			'operaciones' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Numero de operaciones por tipo de pago'
			],
			'importe_total' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe del tipo de pago'
			],
			'importe_real' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe real del tipo de pago'
			],
			'importe_descuento' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total de descuentos para un tipo de pago'
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
