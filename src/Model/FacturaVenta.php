<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class FacturaVenta extends OModel {
	#[OPK(
	  comment: 'Id de la factura',
	  ref: 'factura.id'
	)]
	public ?int $id_factura;

	#[OPK(
	  comment: 'Id de la venta',
	  nullable: false,
	  ref: 'venta.id'
	)]
	public ?int $id_venta;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
