<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Factura extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada factura'
			),
			new OModelField(
				name: 'id_cliente',
				type: OMODEL_NUM,
				nullable: false,
				ref: 'cliente.id',
				comment: 'Id del cliente al que se le emite la factura'
			),
			new OModelField(
				name: 'nombre_apellidos',
				type: OMODEL_TEXT,
				nullable: false,
				size: 150,
				comment: 'Nombre y apellidos del cliente'
			),
			new OModelField(
				name: 'dni_cif',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 10,
				comment: 'DNI/CIF del cliente'
			),
			new OModelField(
				name: 'telefono',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 15,
				comment: 'Teléfono del cliente'
			),
			new OModelField(
				name: 'email',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Email del cliente'
			),
			new OModelField(
				name: 'direccion',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Dirección del cliente'
			),
			new OModelField(
				name: 'codigo_postal',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 10,
				comment: 'Código postal del cliente'
			),
			new OModelField(
				name: 'poblacion',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 50,
				comment: 'Población del cliente'
			),
			new OModelField(
				name: 'provincia',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Id de la provincia del cliente'
			),
			new OModelField(
				name: 'importe',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total de la factura'
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
			),
			new OModelField(
				name: 'deleted_at',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de borrado de la factura'
			)
		);

		parent::load($model);
	}

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
		$c = new Cliente();
		$c->find(['id' => $this->get('id_cliente')]);
		$this->setCliente($c);
	}

	private ?array $ventas = null;

	/**
	 * Obtiene el listado de ventas de una factura
	 *
	 * @return array Listado de ventas
	 */
	public function getVentas(): array {
		if (is_null($this->ventas)) {
			$this->loadVentas();
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
	 * @return void
	 */
	public function loadVentas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `id` IN (SELECT `id_venta` FROM `factura_venta` WHERE `id_factura` = ?) ORDER BY `created_at` ASC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$v = new Venta();
			$v->update($res);
			array_push($list, $v);
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
		$db->query($sql, [$this->get('id')]);

		$this->delete();
	}
}
