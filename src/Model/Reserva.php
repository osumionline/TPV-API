<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\LineaReserva;
use Osumi\OsumiFramework\App\Model\Cliente;

class Reserva extends OModel {
	#[OPK(
	  comment: 'Id único de cada reserva'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del cliente',
	  ref: 'cliente.id'
	)]
	public ?int $id_cliente;

	#[OField(
	  comment: 'Importe total de la reserva',
	  nullable: false,
	  default: 0
	)]
	public ?float $total;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

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
		$this->setLineas(LineaReserva::where(['id_reserva' => $this->id]));
	}

	private ?Cliente $cliente = null;

	/**
	 * Obtiene el cliente de la reserva. Si el cliente existe pero ha sido borrado devuelvo null para indicar que la reserva no tiene cliente.
	 *
	 * @return Cliente Cliente de la reserva
	 */
	public function getCliente(): ?Cliente {
		if (is_null($this->cliente) && !is_null($this->id_cliente)) {
			$this->loadCliente();
		}
		if (!is_null($this->cliente) && is_null($this->cliente->deleted_at)) {
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
		$this->setCliente(Cliente::findOne(['id' => $this->id_cliente]));
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
		$db->query($sql, [$this->id]);

		// Finalmente borro la reserva en si
		$this->delete();
	}
}
