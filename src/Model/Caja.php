<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Caja extends OModel {
	#[OPK(
	  comment: 'Id único para cada cierre de caja'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Fecha de apertura de la caja',
	  nullable: false,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $apertura;

	#[OField(
	  comment: 'Fecha de cierre de la caja',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $cierre;

	#[OField(
	  comment: 'Importe total de ventas para el período de la caja',
	  nullable: false,
	  default: 0
	)]
	public ?float $ventas;

	#[OField(
	  comment: 'Importe total de beneficios para el período de la caja',
	  nullable: false,
	  default: 0
	)]
	public ?float $beneficios;

	#[OField(
	  comment: 'Importe total vendido en efectivo',
	  nullable: false,
	  default: 0
	)]
	public ?float $venta_efectivo;

	#[OField(
	  comment: 'Número de operaciones hechas en efectivo',
	  nullable: false,
	  default: 0
	)]
	public ?int $operaciones_efectivo;

	#[OField(
	  comment: 'Descuento total de las ventas en efectivo',
	  nullable: false,
	  default: 0
	)]
	public ?float $descuento_efectivo;

	#[OField(
	  comment: 'Importe total vendido mediante tipos de pago alternativos',
	  nullable: false,
	  default: 0
	)]
	public ?float $venta_otros;

	#[OField(
	  comment: 'Número de operaciones hechas mediante tipos de pago alternativos',
	  nullable: false,
	  default: 0
	)]
	public ?int $operaciones_otros;

	#[OField(
	  comment: 'Descuento total de las ventas hechas mediante tipos de pago alternativos',
	  nullable: false,
	  default: 0
	)]
	public ?float $descuento_otros;

	#[OField(
	  comment: 'Importe total en pagos de caja',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe_pagos_caja;

	#[OField(
	  comment: 'Número de pagos de caja',
	  nullable: false,
	  default: 0
	)]
	public ?int $num_pagos_caja;

	#[OField(
	  comment: 'Importe total en efectivo en la caja al momento de la apertura',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe_apertura;

	#[OField(
	  comment: 'Importe total en efectivo en la caja al momento del cierre',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe_cierre;

	#[OField(
	  comment: 'Importe real en efectivo en la caja al momento del cierre',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe_cierre_real;

	#[OField(
	  comment: 'Número de monedas de 1 centimo',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe1c;

	#[OField(
	  comment: 'Número de monedas de 2 centimos',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe2c;

	#[OField(
	  comment: 'Número de monedas de 5 centimos',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe5c;

	#[OField(
	  comment: 'Número de monedas de 10 centimos',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe10c;

	#[OField(
	  comment: 'Número de monedas de 20 centimos',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe20c;

	#[OField(
	  comment: 'Número de monedas de 50 centimos',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe50c;

	#[OField(
	  comment: 'Número de monedas de 1 euro',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe1;

	#[OField(
	  comment: 'Número de monedas de 2 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe2;

	#[OField(
	  comment: 'Número de billetes de 5 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe5;

	#[OField(
	  comment: 'Número de billetes de 10 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe10;

	#[OField(
	  comment: 'Número de billetes de 20 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe20;

	#[OField(
	  comment: 'Número de billetes de 50 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe50;

	#[OField(
	  comment: 'Número de billetes de 100 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe100;

	#[OField(
	  comment: 'Número de billetes de 200 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe200;

	#[OField(
	  comment: 'Número de billetes de 500 euros',
	  nullable: false,
	  default: 0
	)]
	public ?int $importe500;

	#[OField(
	  comment: 'Importe retirado de la caja al momento del cierre',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe_retirado;

	#[OField(
	  comment: 'Importe añadido a la caja al momento del cierre',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe_entrada;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

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
		$this->setCajaTipos(CajaTipo::where(['id_caja' => $this->id]));
	}
}
