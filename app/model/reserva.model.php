<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\LineaReserva;
use OsumiFramework\App\Model\Cliente;

class Reserva extends OModel {
	function __construct() {
	$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada reserva'
			),
			new OModelField(
				name: 'id_cliente',
				type: OMODEL_NUM,
				ref: 'cliente.id',
				comment: 'Id del cliente'
			),
			new OModelField(
				name: 'total',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total de la reserva'
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

	private ?array $lineas = null;

	/**
	 * Obtiene el listado de líneas de una reserva
	 *
	 * @return array Listado de líneas
	 */
	public function getLineas(): array {
		if (is_null($this->lineas)) {
			$this->loadLineas();
		}
		return $this->lineas;
	}

	/**
	 * Guarda la lista de líneas
	 *
	 * @param array $l Lista de líneas
	 *
	 * @return void
	 */
	public function setLineas(array $l): void {
		$this->lineas = $l;
	}

	/**
	 * Carga la lista de líneas de una reserva
	 *
	 * @return void
	 */
	public function loadLineas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `linea_reserva` WHERE `id_reserva` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$lr = new LineaReserva();
			$lr->update($res);
			array_push($list, $lr);
		}

		$this->setLineas($list);
	}

	private ?Cliente $cliente = null;

	/**
	 * Obtiene el cliente de la reserva. Si el cliente existe pero ha sido borrado devuelvo null para indicar que la reserva no tiene cliente.
	 *
	 * @return Cliente Cliente de la reserva
	 */
	public function getCliente(): ?Cliente {
		if (is_null($this->cliente) && !is_null($this->get('id_cliente'))) {
			$this->loadCliente();
		}
		if (!is_null($this->cliente) && is_null($this->cliente->get('deleted_at'))) {
			return $this->cliente;
		}
		return null;
	}

	/**
	 * Guarda el cliente de la reserva
	 *
	 * @param Cliente $c Cliente de la reserva
	 *
	 * @return void
	 */
	public function setCliente(Cliente $c): void {
		$this->cliente = $c;
	}

	/**
	 * Carga el cliente de la reserva
	 *
	 * @return void
	 */
	public function loadCliente(): void {
		$c = new Cliente();
		$c->find(['id' => $this->get('id_cliente')]);
		$this->setCliente($c);
	}

	/**
	 * Función para borrar completamente una reserva y sus líneas
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();

		// Borro sus líneas
		$sql = "DELETE FROM `linea_reserva` WHERE `id_reserva` = ?";
		$db->query($sql, [$this->get('id')]);

		// Finalmente borro la reserva en si
		$this->delete();
	}
}
