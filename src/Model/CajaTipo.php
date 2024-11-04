<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class CajaTipo extends OModel {
	#[OPK(
	  comment: 'Id de la caja del desglose',
	  ref: 'caja.id'
	)]
	public ?int $id_caja;

	#[OPK(
	  comment: 'Id del tipo de pago',
	  ref: 'tipo_pago.id'
	)]
	public ?int $id_tipo_pago;

	#[OField(
	  comment: 'Numero de operaciones por tipo de pago',
	  nullable: false,
	  default: 0
	)]
	public ?int $operaciones;

	#[OField(
	  comment: 'Importe del tipo de pago',
	  nullable: true,
	  default: 0
	)]
	public ?float $importe_total;

	#[OField(
	  comment: 'Importe real del tipo de pago',
	  nullable: true,
	  default: 0
	)]
	public ?float $importe_real;

	#[OField(
	  comment: 'Importe total de descuentos para un tipo de pago',
	  nullable: true,
	  default: 0
	)]
	public ?float $importe_descuento;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
