<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;

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
			'descripcion' => [
				'type'     => OModel::LONGTEXT,
				'nullable' => true,
				'default'  => null,
				'comment' => 'Descripción larga del concepto del pago'
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

	/**
	 * Función que comprueba si la salida de caja pertenece a una caja abierta. Si la caja está cerrada la salida no se puede editar.
	 *
	 * @return bool Devuelve si la salida de caja se puede editar o no
	 */
	public function getEditable(): bool {
		$db = new ODB();
		$sql = "SELECT * FROM `caja` WHERE `apertura` < ? AND (`cierre` > ? OR `cierre` IS NULL)";
		$db->query($sql, [$this->get('created_at', 'Y-m-d H:i:s'), $this->get('created_at', 'Y-m-d H:i:s')]);
		if ($res = $db->next()) {
			$caja = new Caja();
			$caja->update($res);
			return is_null($caja->get('cierre'));
		}

		return false;
	}
}
