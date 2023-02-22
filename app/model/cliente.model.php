<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Cliente extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada cliente'
			),
			new OModelField(
				name: 'nombre_apellidos',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
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
				name: 'fact_igual',
				type: OMODEL_BOOL,
				nullable: false,
				default: true,
				comment: 'Indica si los datos de facturación son iguales a los del cliente'
			),
			new OModelField(
				name: 'fact_nombre_apellidos',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 150,
				comment: 'Nombre y apellidos del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_dni_cif',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 10,
				comment: 'DNI/CIF del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_telefono',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 15,
				comment: 'Teléfono del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_email',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Email del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_direccion',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 100,
				comment: 'Dirección del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_codigo_postal',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 10,
				comment: 'Código postal del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_poblacion',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 50,
				comment: 'Población del cliente para la facturación'
			),
			new OModelField(
				name: 'fact_provincia',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Id de la provincia del cliente para la facturación'
			),
			new OModelField(
				name: 'observaciones',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Campo libre para observaciones personales del cliente'
			),
			new OModelField(
				name: 'descuento',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Descuento por defecto para el cliente'
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
				comment: 'Fecha de borrado del cliente'
			)
		);

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

	private ?array $facturas = null;

	/**
	 * Obtiene el listado de facturas de un cliente
	 *
	 * @return array Listado de facturas
	 */
	public function getFacturas(): array {
		if (is_null($this->facturas)) {
			$this->loadFacturas();
		}
		return $this->facturas;
	}

	/**
	 * Guarda la lista de facturas
	 *
	 * @param array $f Lista de facturas
	 *
	 * @return void
	 */
	public function setFacturas(array $f): void {
		$this->facturas = $f;
	}

	/**
	 * Carga la lista de facturas de un cliente
	 *
	 * @return void
	 */
	public function loadFacturas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `factura` WHERE `id_cliente` = ? ORDER BY `created_at` DESC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$f = new Factura();
			$f->update($res);
			array_push($list, $f);
		}

		$this->setFacturas($list);
	}

	/**
	 * Función para obtener los datos del cliente para una factura
	 */
	public function getDatosFactura(): array {
		$datos = [];

		if ($this->get('fact_igual')) {
			$datos = [
				'nombre_apellidos' => $this->get('nombre_apellidos'),
				'dni_cif'          => $this->get('dni_cif'),
				'telefono'         => $this->get('telefono'),
				'email'            => $this->get('email'),
				'direccion'        => $this->get('direccion'),
				'codigo_postal'    => $this->get('codigo_postal'),
				'poblacion'        => $this->get('poblacion'),
				'provincia'        => $this->get('provincia')
			];
		}
		else {
			$datos = [
				'nombre_apellidos' => $this->get('fact_nombre_apellidos'),
				'dni_cif'          => $this->get('fact_dni_cif'),
				'telefono'         => $this->get('fact_telefono'),
				'email'            => $this->get('fact_email'),
				'direccion'        => $this->get('fact_direccion'),
				'codigo_postal'    => $this->get('fact_codigo_postal'),
				'poblacion'        => $this->get('fact_poblacion'),
				'provincia'        => $this->get('fact_provincia')
			];
		}

		return $datos;
	}

	/**
	 * Función para borrar definitivamente un cliente y todas sus implicaciones
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();
		$sql = "UPDATE `venta` SET `id_cliente` = NULL WHERE `id_cliente` = ?";
		$db->query($sql, [$this->get('id')]);

		$facturas = $this->getFacturas();
		foreach ($facturas as $factura) {
			$factura->deleteFull();
		}

		$this->delete();
	}
}
