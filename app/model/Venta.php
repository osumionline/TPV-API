<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Model\TipoPago;

class Venta extends OModel {
	/**
	 * Configures current model object based on data-base table structure
	 */
	function __construct() {
		$table_name  = 'venta';
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único de cada venta'
			],
			'id_empleado' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'empleado.id',
				'comment' => 'Id del empleado que realiza la venta'
			],
			'id_cliente' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'cliente.id',
				'comment' => 'Id del cliente'
			],
			'total' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Importe total de la venta'
			],
			'entregado' => [
				'type'    => OModel::FLOAT,
				'nullable' => false,
				'default' => '0',
				'comment' => 'Importe entregado por el cliente'
			],
			'pago_mixto' => [
				'type'    => OModel::BOOL,
				'nullable' => false,
				'default' => false,
				'comment' => 'Indica si se ha hecho un pago mixto'
			],
			'id_tipo_pago' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'ref' => 'tipo_pago.id',
				'comment' => 'Id del tipo de pago'
			],
			'entregado_otro' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Cantidad pagada mediante tipo de pago alternativo'
			],
			'saldo' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Saldo en caso de que el ticket sea un vale'
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
				'comment' => 'Fecha de borrado de la venta'
			]
		];

		parent::load($table_name, $model);
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
}
