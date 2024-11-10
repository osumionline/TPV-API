<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Factura;

class Cliente extends OModel {
	#[OPK(
	  comment: 'Id único de cada cliente'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre y apellidos del cliente',
	  nullable: false,
	  max: 150,
	  default: null
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
	  comment: 'Indica si los datos de facturación son iguales a los del cliente',
	  nullable: false,
	  default: true
	)]
	public ?bool $fact_igual;

	#[OField(
	  comment: 'Nombre y apellidos del cliente para la facturación',
	  nullable: true,
	  max: 150,
	  default: null
	)]
	public ?string $fact_nombre_apellidos;

	#[OField(
	  comment: 'DNI/CIF del cliente para la facturación',
	  nullable: true,
	  max: 10,
	  default: null
	)]
	public ?string $fact_dni_cif;

	#[OField(
	  comment: 'Teléfono del cliente para la facturación',
	  nullable: true,
	  max: 15,
	  default: null
	)]
	public ?string $fact_telefono;

	#[OField(
	  comment: 'Email del cliente para la facturación',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $fact_email;

	#[OField(
	  comment: 'Dirección del cliente para la facturación',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $fact_direccion;

	#[OField(
	  comment: 'Código postal del cliente para la facturación',
	  nullable: true,
	  max: 10,
	  default: null
	)]
	public ?string $fact_codigo_postal;

	#[OField(
	  comment: 'Población del cliente para la facturación',
	  nullable: true,
	  max: 50,
	  default: null
	)]
	public ?string $fact_poblacion;

	#[OField(
	  comment: 'Id de la provincia del cliente para la facturación',
	  nullable: true,
	  default: null
	)]
	public ?int $fact_provincia;

	#[OField(
	  comment: 'Campo libre para observaciones personales del cliente',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $observaciones;

	#[OField(
	  comment: 'Descuento por defecto para el cliente',
	  nullable: false,
	  default: 0
	)]
	public ?int $descuento;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado del cliente',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

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
		$this->setVentas(Venta::where(['id_cliente' => $this->id], ['order_by' => 'created_at#desc']));
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
		$this->setFacturas(Factura::where(['id_cliente' => $this->id], ['order_by' => 'created_at#desc']));
	}

	/**
	 * Función para obtener los datos del cliente para una factura
	 */
	public function getDatosFactura(): array {
		$datos = [];

		if ($this->fact_igual) {
			$datos = [
				'nombre_apellidos' => $this->nombre_apellidos,
				'dni_cif'          => $this->dni_cif,
				'telefono'         => $this->telefono,
				'email'            => $this->email,
				'direccion'        => $this->direccion,
				'codigo_postal'    => $this->codigo_postal,
				'poblacion'        => $this->poblacion,
				'provincia'        => $this->provincia
			];
		}
		else {
			$datos = [
				'nombre_apellidos' => $this->fact_nombre_apellidos,
				'dni_cif'          => $this->fact_dni_cif,
				'telefono'         => $this->fact_telefono,
				'email'            => $this->fact_email,
				'direccion'        => $this->fact_direccion,
				'codigo_postal'    => $this->fact_codigo_postal,
				'poblacion'        => $this->fact_poblacion,
				'provincia'        => $this->fact_provincia
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
		$db->query($sql, [$this->id]);

		$facturas = $this->getFacturas();
		foreach ($facturas as $factura) {
			$factura->deleteFull();
		}

		$this->delete();
	}
}
