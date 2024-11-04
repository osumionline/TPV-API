<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\LineaVenta;
use Osumi\OsumiFramework\App\Model\TipoPago;
use Osumi\OsumiFramework\App\Model\Cliente;
use Osumi\OsumiFramework\App\Model\Empleado;
use Osumi\OsumiFramework\App\Model\Caja;
use Osumi\OsumiFramework\App\Model\Factura;

class Venta extends OModel {
	#[OPK(
	  comment: 'Id único de cada venta'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Número de venta',
	  nullable: false
	)]
	public ?int $num_venta;

	#[OField(
	  comment: 'Id del empleado que realiza la venta',
	  nullable: true,
	  ref: 'empleado.id',
	  default: null
	)]
	public ?int $id_empleado;

	#[OField(
	  comment: 'Id del cliente',
	  nullable: true,
	  ref: 'cliente.id',
	  default: null
	)]
	public ?int $id_cliente;

	#[OField(
	  comment: 'Importe total de la venta',
	  nullable: false,
	  default: 0
	)]
	public ?float $total;

	#[OField(
	  comment: 'Importe entregado por el cliente',
	  nullable: false,
	  default: 0
	)]
	public ?float $entregado;

	#[OField(
	  comment: 'Indica si se ha hecho un pago mixto',
	  nullable: false,
	  default: false
	)]
	public ?bool $pago_mixto;

	#[OField(
	  comment: 'Id del tipo de pago',
	  nullable: true,
	  ref: 'tipo_pago.id',
	  default: null
	)]
	public ?int $id_tipo_pago;

	#[OField(
	  comment: 'Cantidad pagada mediante tipo de pago alternativo',
	  nullable: true,
	  default: null
	)]
	public ?float $entregado_otro;

	#[OField(
	  comment: 'Saldo en caso de que el ticket sea un vale',
	  nullable: true,
	  default: null
	)]
	public ?float $saldo;

	#[OField(
	  comment: 'Indica si la venta ha sido facturada',
	  nullable: false,
	  default: false
	)]
	public ?bool $facturada;

	#[OField(
	  comment: 'Huella de TicketBai',
	  nullable: true,
	  max: 50,
	  default: null
	)]
	public ?string $tbai_huella;

	#[OField(
	  comment: 'Código QR de TicketBai',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $tbai_qr;

	#[OField(
	  comment: 'URL del ticket en Batuz',
	  nullable: true,
	  max: 255,
	  default: null
	)]
	public ?string $tbai_url;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado de la venta',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

	/**
	 * Obtiene el beneficio total de la venta teniendo en cuenta las líneas de la venta y sus costes e importes
	 *
	 * @return float Beneficio de la venta
	 */
	public function getBeneficio(): float {
		$beneficio = 0;
		foreach ($this->getLineas() as $linea) {
			$beneficio += $linea->getBeneficio();
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
		if ($this->pago_mixto) {
			return $this->entregado;
		}
		else {
			// Si no hay tipo de pago alternativo, todo es efectivo
			if (is_null($this->id_tipo_pago)) {
				return $this->total;
			}
			else {
				// Si el tipo de pago alternativo afecta a caja, va a efectivo
				if ($this->getTipoPago()->afecta_caja) {
					return $this->total;
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
			$descuento += ($linea->pvp * $linea->descuento) + $linea->importe_descuento;
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
		if ($this->pago_mixto) {
			return ($this->total - $this->entregado);
		}
		else {
			// Si hay tipo de pago alternativo, todo es otros
			if (!is_null($this->id_tipo_pago)) {
				// Si el tipo de pago alternativo no afecta a caja, va a otros
				if (!$this->getTipoPago()->afecta_caja) {
					return $this->total;
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
		if (is_null($this->id_tipo_pago)) {
			return $this->total - $this->entregado;
		}
		else {
			// Si el pago es mixto el cambio será el total - pagado con tipo de pago alternativo - entregado
			if ($this->pago_mixto) {
				return $this->total - $this->entregado_otro - $this->entregado;
			}
			// Si no tiene pago mixto el cambio es 0 por que ha pagado todo usando un tipo de pago alternativo
			else {
				return 0;
			}
		}
	}

	private bool $hide_gifts = false;

	/**
	 * Indica si se deben cargar las líneas de tipo regalo
	 *
	 * @param bool $mode Indica si cargar las líneas de tipo regalo
	 *
	 * @return void
	 */
	public function setHideGifts($mode): void {
		$this->hide_gifts = $mode;
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
		$this->lineas[] = $l;
	}

	/**
	 * Carga la lista de líneas de una venta
	 *
	 * @return void
	 */
	public function loadLineas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `linea_venta` WHERE `id_venta` = ?";
		if  ($this->hide_gifts) {
			$sql .= " AND `regalo` = 0";
		}
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = LineaVenta::from($res);
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
		if (is_null($this->tipo_pago) && !is_null($this->id_tipo_pago)) {
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
		$this->setTipoPago(TipoPago::findOne(['id' => $this->id_tipo_pago]));
	}

	/**
	 * Obtiene el nombre del tipo de pago, o "Efectivo" si se ha hecho en efectivo
	 *
	 * @return string Devuelve el nombre del tipo de pago
	 */
	public function getNombreTipoPago(): string {
		if (is_null($this->id_tipo_pago)) {
			return "Efectivo";
		}
		else {
			return $this->getTipoPago()->nombre;
		}
	}

	private ?Cliente $cliente = null;

	/**
	 * Obtiene el cliente de la venta. Si el cliente existe pero ha sido borrado devuelvo null para indicar que la venta no tiene cliente.
	 *
	 * @return Cliente Cliente de la venta
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
		$this->setCliente(Cliente::findOne(['id' => $this->id_cliente]));
	}

	private ?Empleado $empleado = null;

	/**
	 * Obtiene el empleado de la venta. Si el empleado existe pero ha sido borrado devuelvo null para indicar que la venta no tiene empleado.
	 *
	 * @return Empleado Empleado de la venta
	 */
	public function getEmpleado(): ?Empleado {
		if (is_null($this->empleado) && !is_null($this->id_empleado)) {
			$this->loadEmpleado();
		}
		if (!is_null($this->empleado) && is_null($this->empleado->deleted_at)) {
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
		$this->setEmpleado(Empleado::findOne(['id' => $this->id_empleado]));
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
			$caja = Caja::from($res);
			return is_null($caja->cierre);
		}

		return false;
	}

	/**
	 * Si la venta está en una factura, obtiene dicha factura
	 *
	 * @return Factura Factura en la que está la venta o null si no ha sido facturada
	 */
	public function getFactura(): ?Factura {
		$fv = FacturaVenta::findOne(['id_venta' => $this->id]);
		if (!is_null($fv)) {
			return Factura::findOne(['id' => $fv->id_factura]);
		}
		return null;
	}

	private string $status_factura = 'no';

	/**
	 * Función para asignar el estado de la venta en una factura
	 *
	 * @param string $status_factura Estado de la factura (no - Venta no facturada / si - Venta facturada / used: Venta en una factura no terminada)
	 *
	 * @return void
	 */
	public function setStatusFactura(string $status_factura): void {
		$this->status_factura = $status_factura;
	}

	/**
	 * Función para obtener el estado de la venta en una factura
	 *
	 * @return string Estado de la venta en una factura
	 */
	public function getStatusFactura(): string {
		return $this->status_factura;
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
		$db->query($sql, [$this->id]);

		// A continuación borro sus líneas
		$sql = "DELETE FROM `linea_venta` WHERE `id_venta` = ?";
		$db->query($sql, [$this->id]);

		// Finalmente borro la venta en si
		$this->delete();
	}

	/**
	 * Función para obtener los datos necesarios para enviar a TicketBai
	 *
	 * @return array Array de datos para TicketBai
	 */
	public function getDatosTBai(): array {
		$ret = [
			'fecha'                     => $this->get('created_at', 'd/m/Y'),
			'hora'                      => $this->get('created_at', 'H:i:s'),
			'nif'                       => '',
			'nombre'                    => '',
			'direccion'                 => '',
			'cp'                        => '',
			'serie'                     => 'TPV01',
			'numero'                    => sprintf('%06d', $this->num_venta),
			'simplificada'              => true,
			'modo_recargo_equivalencia' => true,
			'rectificativa'             => false,
			'importacion'               => false,
			'intracomunitaria'          => false,
			'retencion'                 => 0,
			'lineas'                    => [],
			'total_factura'             => $this->total
		];

		$lineas = $this->getLineas();

		foreach ($lineas as $linea) {
			$importe_siva = $linea->pvp / (1 + ($linea->iva / 100));

			$datos_linea = [
				'iva'              => ($linea->iva === 0) ? 21 : $linea->iva,
				'descripcion'      => html_entity_decode($linea->nombre_articulo),
				'cantidad'         => $linea->unidades,
				'importe_unitario' => round($importe_siva, 4),
				'tipo_iva'         => $linea->iva,
				'tipo_req'         => 0
			];

			$ret['lineas'][] = $datos_linea;

			if ($linea->descuento !== 0 || !is_null($linea->importe_descuento)) {
				if ($linea->descuento !== 0) {
					$importe_desc = $importe_siva * ($linea->descuento / 100);
				}
				if (!is_null($linea->importe_descuento)) {
					$importe_desc = $linea->importe_descuento / $linea->unidades;
					$importe_desc = $importe_desc / (1 + ($linea->iva / 100));
				}

				$datos_linea = [
					'iva'              => ($linea->iva === 0) ? 21 : $linea->iva,
					'descripcion'      => html_entity_decode('Descuento - ' . $linea->nombre_articulo),
					'cantidad'         => $linea->unidades,
					'importe_unitario' => round(-1 * $importe_desc, 4),
					'tipo_iva'         => $linea->iva,
					'tipo_req'         => 0
				];

				$ret['lineas'][] = $datos_linea;
			}
		}

		return $ret;
	}
}
