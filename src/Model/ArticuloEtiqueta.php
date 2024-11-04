<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class ArticuloEtiqueta extends OModel {
	#[OPK(
	  comment: 'Id del artículo',
	  nullable: false,
	  ref: 'articulo.id'
	)]
	public ?int $id_articulo;

	#[OPK(
	  comment: 'Id de la etiqueta',
	  nullable: false,
	  ref: 'etiqueta.id'
	)]
	public ?int $id_etiqueta;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
