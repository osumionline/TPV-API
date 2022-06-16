<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;

class Cliente extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada cliente'
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
			'fact_igual' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => true,
				'comment' => 'Indica si los datos de facturación son iguales a los del cliente'
			],
			'fact_nombre_apellidos' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 150,
				'comment' => 'Nombre y apellidos del cliente para la facturación'
			],
			'fact_dni_cif' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'DNI/CIF del cliente para la facturación'
			],
			'fact_telefono' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono del cliente para la facturación'
			],
			'fact_email' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Email del cliente para la facturación'
			],
			'fact_direccion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección del cliente para la facturación'
			],
			'fact_codigo_postal' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 10,
				'comment' => 'Código postal del cliente para la facturación'
			],
			'fact_poblacion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 50,
				'comment' => 'Población del cliente para la facturación'
			],
			'fact_provincia' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Id de la provincia del cliente para la facturación'
			],
			'observaciones' => [
				'type'    => OModel::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Campo libre para observaciones personales del cliente'
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
				'type'     => OModel::DATE,
				'nullable' => true,
				'default'  => null,
				'comment'  => 'Fecha de borrado del cliente'
			]
		];

		parent::load($model);
	}

	private ?array $ventas = null;

	/**
	 * Obtiene el listado de ventas de un cliente
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
	 * Carga la lista de ventas de un cliente
	 *
	 * @return void
	 */
	public function loadVentas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `id_cliente` = ? ORDER BY `created_at` DESC";
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
	 * Obtiene la última venta de un cliente, si tiene
	 *
	 * @return Venta Datos de la última venta
	 */
	public function getUltimaVenta(): ?Venta {
		if (is_null($this->ventas)) {
			$this->loadVentas();
		}
		if (count($this->ventas) > 0) {
			return $this->ventas[0];
		}
		return null;
	}
}
