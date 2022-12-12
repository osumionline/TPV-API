<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class Caja extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada cierre de caja'
			),
			new OModelField(
				name: 'apertura',
				type: OMODEL_DATE,
				nullable: false,
				default: null,
				comment: 'Fecha de apertura de la caja'
			),
			new OModelField(
				name: 'cierre',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de cierre de la caja'
			),
			new OModelField(
				name: 'ventas',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total de ventas para el período de la caja'
			),
			new OModelField(
				name: 'beneficios',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total de beneficios para el período de la caja'
			),
			new OModelField(
				name: 'venta_efectivo',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total vendido en efectivo'
			),
			new OModelField(
				name: 'operaciones_efectivo',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Número de operaciones hechas en efectivo'
			),
			new OModelField(
				name: 'descuento_efectivo',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Descuento total de las ventas en efectivo'
			),
			new OModelField(
				name: 'venta_otros',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total vendido mediante tipos de pago alternativos'
			),
			new OModelField(
				name: 'operaciones_otros',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Número de operaciones hechas mediante tipos de pago alternativos'
			),
			new OModelField(
				name: 'descuento_otros',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Descuento total de las ventas hechas mediante tipos de pago alternativos'
			),
			new OModelField(
				name: 'importe_pagos_caja',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total en pagos de caja'
			),
			new OModelField(
				name: 'num_pagos_caja',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Número de pagos de caja'
			),
			new OModelField(
				name: 'importe_apertura',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total en efectivo en la caja al momento de la apertura'
			),
			new OModelField(
				name: 'importe_cierre',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe total en efectivo en la caja al momento del cierre'
			),
			new OModelField(
				name: 'importe_cierre_real',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe real en efectivo en la caja al momento del cierre'
			),
			new OModelField(
				name: 'importe_retirado',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Importe retirado de la caja al momento del cierre'
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
			)
		);

		parent::load($model);
	}

	private ?array $caja_tipos = null;

	/**
	 * Obtiene el listado de tipos de pago para un cierre de caja
	 *
	 * @return array Listado de tipos de pago para un cierre de caja
	 */
	public function getCajaTipos(): array {
		if (is_null($this->caja_tipos)) {
			$this->loadCajaTipos();
		}
		return $this->caja_tipos;
	}

	/**
	 * Guarda la lista de tipos de pago para un cierre de caja
	 *
	 * @param array $cb Lista de tipos de pago para un cierre de caja
	 *
	 * @return void
	 */
	public function setCajaTipos(array $ct): void {
		$this->caja_tipos = $ct;
	}

	/**
	 * Carga la lista de códigos de tipos de pago para un cierre de caja
	 *
	 * @return void
	 */
	public function loadCajaTipos(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `caja_tipo` WHERE `id_caja` = ?";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$ct = new CajaTipo();
			$ct->update($res);
			array_push($list, $ct);
		}

		$this->setCajaTipos($list);
	}
}
