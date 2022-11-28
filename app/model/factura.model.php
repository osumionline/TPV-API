<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;

class Factura extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada factura'
			],
			'id_cliente' => [
				'type'    => OModel::NUM,
				'nullable' => false,
				'default' => null,
				'ref' => 'cliente.id',
				'comment' => 'Id del cliente al que se le emite la factura'
			],
			'nombre_apellidos' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 150,
				'comment' => 'Nombre y apellidos del cliente'
			],
			'dni_cif' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'DNI/CIF del cliente'
			],
			'telefono' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono del cliente'
			],
			'email' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Email del cliente'
			],
			'direccion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección del cliente'
			],
			'codigo_postal' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'Código postal del cliente'
			],
			'poblacion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 50,
				'comment' => 'Población del cliente'
			],
			'provincia' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Id de la provincia del cliente'
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
			],
			'deleted_at' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de baja del empleado'
			]
		];

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
}
