<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Etiqueta extends OModel {
	#[OPK(
	  comment: 'Id único para cada etiqueta'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Texto de la etiqueta',
	  nullable: false,
	  max: 50
	)]
	public ?string $texto;

	#[OField(
	  comment: 'Slug del texto de la etiqueta',
	  nullable: false,
	  max: 50
	)]
	public ?string $slug;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
