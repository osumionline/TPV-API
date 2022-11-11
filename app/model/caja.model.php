<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;

class Caja extends OModel {
	function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada cierre de caja'
			],
			'apertura' => [
				'type'    => OModel::DATE,
				'nullable' => false,
				'default' => null,
				'comment' => 'Fecha de apertura de la caja'
			],
			'cierre' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de cierre de la caja'
			],
			'ventas' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total de ventas para el período de la caja'
			],
			'beneficios' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total de beneficios para el período de la caja'
			],
			'venta_efectivo' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total vendido en efectivo'
			],
			'operaciones_efectivo' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Número de operaciones hechas en efectivo'
			],
			'descuento_efectivo' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Descuento total de las ventas en efectivo'
			],
			'venta_otros' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total vendido mediante tipos de pago alternativos'
			],
			'operaciones_otros' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Número de operaciones hechas mediante tipos de pago alternativos'
			],
			'descuento_otros' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Descuento total de las ventas hechas mediante tipos de pago alternativos'
			],
			'importe_pagos_caja' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total en pagos de caja'
			],
			'num_pagos_caja' => [
				'type'     => OModel::NUM,
				'nullable' => false,
				'default'  => 0,
				'comment'  => 'Número de pagos de caja'
			],
			'importe_apertura' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total en efectivo en la caja al momento de la apertura'
			],
			'importe_cierre' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe total en efectivo en la caja al momento del cierre'
			],
			'importe_cierre_real' => [
				'type'    => OModel::FLOAT,
				'nullable' => true,
				'default' => 0,
				'comment' => 'Importe real en efectivo en la caja al momento del cierre'
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
			]
		];

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
