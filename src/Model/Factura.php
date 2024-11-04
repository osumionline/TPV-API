<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Cliente;
use Osumi\OsumiFramework\App\Model\Venta;

class Factura extends OModel {
	#[OPK(
	  comment: 'Id único para cada factura'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del cliente al que se le emite la factura',
	  nullable: false,
	  ref: 'cliente.id'
	)]
	public ?int $id_cliente;

	#[OField(
	  comment: 'Número de factura',
	  nullable: true,
	  default: null
	)]
	public ?int $num_factura;

	#[OField(
	  comment: 'Nombre y apellidos del cliente',
	  nullable: false,
	  max: 150
	)]
	public ?string $nombre_apellidos;

	#[OField(
	  comment: 'DNI/CIF del cliente',
	  nullable: true,
	  max: 10,
	  default: null
	)]
	public ?string $dni_cif;

	#[OField(
	  comment: 'Teléfono del cliente',
	  nullable: true,
	  max: 15,
	  default: null
	)]
	public ?string $telefono;

	#[OField(
	  comment: 'Email del cliente',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $email;

	#[OField(
	  comment: 'Dirección del cliente',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $direccion;

	#[OField(
	  comment: 'Código postal del cliente',
	  nullable: true,
	  max: 10,
	  default: null
	)]
	public ?string $codigo_postal;

	#[OField(
	  comment: 'Población del cliente',
	  nullable: true,
	  max: 50,
	  default: null
	)]
	public ?string $poblacion;

	#[OField(
	  comment: 'Id de la provincia del cliente',
	  nullable: true,
	  default: null
	)]
	public ?int $provincia;

	#[OField(
	  comment: 'Importe total de la factura',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe;

	#[OField(
	  comment: 'Indica si la factura ha sido impresa 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $impresa;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado de la factura',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

	private ?Cliente $cliente = null;

	/**
	 * Obtiene el cliente de la factura
	 *
	 * @return Cliente Cliente de la factura
	 */
	public function getCliente(): Cliente {
		if (is_null($this->cliente)) {
			$this->loadCliente();
		}
		return $this->cliente;
	}

	/**
	 * Guarda el cliente de la factura
	 *
	 * @param Cliente $c Cliente de la factura
	 *
	 * @return void
	 */
	public function setCliente(Cliente $c): void {
		$this->cliente = $c;
	}

	/**
	 * Carga el cliente de la factura
	 *
	 * @return void
	 */
	public function loadCliente(): void {
		$this->setCliente(Cliente::fineOne(['id' => $this->id_cliente]));
	}

	private ?array $ventas = null;

	/**
	 * Obtiene el listado de ventas de una factura
	 *
	 * @param string $option Indica si mostrar los regalos
	 *
	 * @return array Listado de ventas
	 */
	public function getVentas(string $option = ''): array {
		if (is_null($this->ventas)) {
			$this->loadVentas($option);
		}
		return $this->ventas;
	}

	/**
	 * Guarda la lista de ventas
	 *
	 * @param array $v Lista de ventas
	 *
	 * @return void
	 */
	public function setVentas(array $v): void {
		$this->ventas = $v;
	}

	/**
	 * Carga la lista de ventas de una factura
	 *
	 * @param string $option Indica si mostrar los regalos
	 *
	 * @return void
	 */
	public function loadVentas(string $option = ''): void {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `id` IN (SELECT `id_venta` FROM `factura_venta` WHERE `id_factura` = ?) ORDER BY `created_at` ASC";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res=$db->next()) {
			$v = Venta::from($res);
			if ($option == 'hideGifts') {
				$v->setHideGifts(true);
			}
			$list[] = $v;
		}

		$this->setVentas($list);
	}

	/**
	 * Función para borrar definitivamente una factura
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();
		$sql = "DELETE FROM `factura_venta` WHERE `id_factura` = ?";
		$db->query($sql, [$this->id]);

		$this->delete();
	}
}
