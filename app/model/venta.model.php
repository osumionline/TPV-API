<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Model\TipoPago;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Model\Caja;

class Venta extends OModel {
	function __construct() {
	$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada venta'
			),
			new OModelField(
				name: 'id_empleado',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'empleado.id',
				comment: 'Id del empleado que realiza la venta'
			),
			new OModelField(
				name: 'id_cliente',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'cliente.id',
				comment: 'Id del cliente'
			),
			new OModelField(
				name: 'total',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total de la venta'
			),
			new OModelField(
				name: 'entregado',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe entregado por el cliente'
			),
			new OModelField(
				name: 'pago_mixto',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si se ha hecho un pago mixto'
			),
			new OModelField(
				name: 'id_tipo_pago',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				ref: 'tipo_pago.id',
				comment: 'Id del tipo de pago'
			),
			new OModelField(
				name: 'entregado_otro',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Cantidad pagada mediante tipo de pago alternativo'
			),
			new OModelField(
				name: 'saldo',
				type: OMODEL_FLOAT,
				nullable: true,
				default: null,
				comment: 'Saldo en caso de que el ticket sea un vale'
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
				comment: 'Fecha de borrado de la venta'
			)
		);

		parent::load($model);
	}

	/**
	 * Obtiene el beneficio total de la venta teniendo en cuenta las líneas de la venta y sus costes e importes
	 *
	 * @return float Beneficio de la venta
	 */
	public function getBeneficio(): float {
		$beneficio = 0;
		foreach ($this->getLineas() as $linea) {
			$beneficio += ($linea->get('importe') - $linea->get('puc'));
		}
		return $beneficio;
	}

	/**
	 * Obtiene la cantidad pagada en efectivo en la venta
	 *
	 * @return float Cantidad pagada en efectivo en la venta
	 */
	public function getVentaEfectivo(): float {
		// Si el pago es mixto, lo entregado va a efectivo y la diferencia (total - entregado) será lo que ha pagado aparte
		if ($this->get('pago_mixto')) {
			return $this->get('entregado');
		}
		else {
			// Si no hay tipo de pago alternativo, todo es efectivo
			if (is_null($this->get('id_tipo_pago'))) {
				return $this->get('total');
			}
			else {
				// Si el tipo de pago alternativo afecta a caja, va a efectivo
				if ($this->getTipoPago()->get('afecta_caja')) {
					return $this->get('total');
				}
			}
		}
		return 0;
	}

	/**
	 * Obtiene la cantidad descontada en la venta
	 *
	 * @return float Cantidad descontada en la venta
	 */
	public function getVentaDescuento(): float {
		$descuento = 0;
		foreach ($this->getLineas() as $linea) {
			$descuento += ($linea->get('pvp') * $linea->get('descuento')) + $linea->get('importe_descuento');
		}
		return $descuento;
	}

	/**
	 * Obtiene la cantidad pagada con otros tipos de pago en la venta
	 *
	 * @return float Cantidad pagada con otros tipos de pago en la venta
	 */
	public function getVentaOtros(): float {
		// Si el pago es mixto, lo entregado va a efectivo y la diferencia (total - entregado) será lo que ha pagado aparte
		if ($this->get('pago_mixto')) {
			return ($this->get('total') - $venta->get('entregado'));
		}
		else {
			// Si hay tipo de pago alternativo, todo es otros
			if (!is_null($this->get('id_tipo_pago'))) {
				// Si el tipo de pago alternativo no afecta a caja, va a otros
				if (!$this->getTipoPago()->get('afecta_caja')) {
					return $this->get('total');
				}
			}
		}
		return 0;
	}

	/**
	 * Obtiene el cambio de la venta
	 *
	 * @return float Cantidad en concepto de cambio
	 */
	public function getCambio(): float {
		// Si no tiene tipo de pago alternativo el cambio es total - entregado
		if (is_null($this->get('id_tipo_pago'))) {
			return $this->get('total') - $this->get('entregado');
		}
		else {
			// Si el pago es mixto el cambio será el total - pagado con tipo de pago alternativo - entregado
			if ($this->get('pago_mixto')) {
				return $this->get('total') - $this->get('entregado_otro') - $this->get('entregado');
			}
			// Si no tiene pago mixto el cambio es 0 por que ha pagado todo usando un tipo de pago alternativo
			else {
				return 0;
			}
		}
	}

	private ?array $lineas = null;

	/**
	 * Obtiene el listado de líneas de una venta
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
	 * Función para añadir una nueva línea a la venta
	 *
	 * @param LineaVenta $l Nueva línea de la venta
	 *
	 * @return void
	 */
	public function addLinea(LineaVenta $l): void {
		if (is_null($this->lineas)) {
			$this->lineas = [];
		}
		array_push($this->lineas, $l);
	}

	/**
	 * Carga la lista de líneas de una venta
	 *
	 * @return void
	 */
	public function loadLineas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `linea_venta` WHERE `id_venta` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$lv = new LineaVenta();
			$lv->update($res);
			array_push($list, $lv);
		}

		$this->setLineas($list);
	}

	private ?TipoPago $tipo_pago = null;

	/**
	 * Obtiene el tipo de pago alternativo de la venta
	 *
	 * @return TipoPago Tipo de pago alternativo de la venta
	 */
	public function getTipoPago(): ?TipoPago {
		if (is_null($this->tipo_pago) && !is_null($this->get('id_tipo_pago'))) {
			$this->loadTipoPago();
		}
		return $this->tipo_pago;
	}

	/**
	 * Guarda el tipo de pago alternativo de la venta
	 *
	 * @param TipoPago $tp Tipo de pago alternativo de la venta
	 *
	 * @return void
	 */
	public function setTipoPago(TipoPago $tp): void {
		$this->tipo_pago = $tp;
	}

	/**
	 * Carga el tipo de pago alternativo de la venta
	 *
	 * @return void
	 */
	public function loadTipoPago(): void {
		$tp = new TipoPago();
		$tp->find(['id' => $this->get('id_tipo_pago')]);
		$this->setTipoPago($tp);
	}

	/**
	 * Obtiene el nombre del tipo de pago, o "Efectivo" si se ha hecho en efectivo
	 *
	 * @return string Devuelve el nombre del tipo de pago
	 */
	public function getNombreTipoPago(): string {
		if (is_null($this->get('id_tipo_pago'))) {
			return "Efectivo";
		}
		else {
			return $this->getTipoPago()->get('nombre');
		}
	}

	private ?Cliente $cliente = null;

	/**
	 * Obtiene el cliente de la venta. Si el cliente existe pero ha sido borrado devuelvo null para indicar que la venta no tiene cliente.
	 *
	 * @return Cliente Cliente de la venta
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
	 * Guarda el cliente de la venta
	 *
	 * @param Cliente $c Cliente de la venta
	 *
	 * @return void
	 */
	public function setCliente(Cliente $c): void {
		$this->cliente = $c;
	}

	/**
	 * Carga el cliente de la venta
	 *
	 * @return void
	 */
	public function loadCliente(): void {
		$c = new Cliente();
		$c->find(['id' => $this->get('id_cliente')]);
		$this->setCliente($c);
	}

	private ?Empleado $empleado = null;

	/**
	 * Obtiene el empleado de la venta. Si el empleado existe pero ha sido borrado devuelvo null para indicar que la venta no tiene empleado.
	 *
	 * @return Empleado Empleado de la venta
	 */
	public function getEmpleado(): ?Empleado {
		if (is_null($this->empleado) && !is_null($this->get('id_empleado'))) {
			$this->loadEmpleado();
		}
		if (is_null($this->empleado->get('deleted_at'))) {
			return $this->empleado;
		}
		return null;
	}

	/**
	 * Guarda el empleado de la venta
	 *
	 * @param Empleado $e Empleado de la venta
	 *
	 * @return void
	 */
	public function setEmpleado(Empleado $e): void {
		$this->empleado = $e;
	}

	/**
	 * Carga el empleado de la venta
	 *
	 * @return void
	 */
	public function loadEmpleado(): void {
		$e = new Empleado();
		$e->find(['id' => $this->get('id_empleado')]);
		$this->setEmpleado($e);
	}

	/**
	 * Función que comprueba si la venta pertenece a una caja abierta. Si la caja está cerrada la venta no se puede editar.
	 *
	 * @return bool Devuelve si la venta se puede editar o no
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

	/**
	 * Función para borrar completamente una venta y sus líneas
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();

		// Primero borro la venta de las posibles facturas en las que pueda estar
		$sql = "DELETE FROM `factura_venta` WHERE `id_venta` = ?";
		$db->query($sql, [$this->get('id')]);

		// A continuación borro sus líneas
		$sql = "DELETE FROM `linea_venta` WHERE `id_venta` = ?";
		$db->query($sql, [$this->get('id')]);

		// Finalmente borro la venta en si
		$this->delete();
	}
}
