<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Caja;

class PagoCaja extends OModel {
	#[OPK(
	  comment: 'Id único para cada pago de caja'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Concepto del pago',
	  nullable: false,
	  max: 250,
	  default: null
	)]
	public ?string $concepto;

	#[OField(
	  comment: 'Importe de dinero sacado de la caja para realizar el pago',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe;

	#[OField(
	  comment: 'Descripción larga del concepto del pago',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $descripcion;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

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
			$caja = Caja::from($res);
			return is_null($caja->cierre);
		}

		return false;
	}
}
