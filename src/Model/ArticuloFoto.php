<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ArticuloFoto extends OModel {
	#[OPK(
	  comment: 'Id único para cada foto',
	  ref: 'foto.id'
	)]
	public ?int $id_foto;

	#[OPK(
	  comment: 'Id del artículo al que pertenece la foto',
	  nullable: false,
	  ref: 'articulo.id'
	)]
	public ?int $id_articulo;

	#[OField(
	  comment: 'Orden de la foto entre todas las fotos de un artículo',
	  nullable: false,
	  default: 0
	)]
	public ?int $orden;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
