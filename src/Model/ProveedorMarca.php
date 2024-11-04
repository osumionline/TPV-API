<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ProveedorMarca extends OModel {
	#[OPK(
	  comment: 'Id del proveedor',
	  ref: 'proveedor.id'
	)]
	public ?int $id_proveedor;

	#[OPK(
	  comment: 'Id de la marca',
	  ref: 'marca.id'
	)]
	public ?int $id_marca;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
