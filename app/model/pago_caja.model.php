<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class PagoCaja extends OModel {
	function __construct() {
	$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada pago de caja'
			),
			new OModelField(
				name: 'concepto',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 250,
				comment: 'Concepto del pago'
			),
			new OModelField(
				name: 'importe',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe de dinero sacado de la caja para realizar el pago'
			),
			new OModelField(
				name: 'descripcion',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Descripción larga del concepto del pago'
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
