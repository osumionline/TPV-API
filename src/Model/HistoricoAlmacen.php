<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class HistoricoAlmacen extends OModel {
	#[OPK(
	  comment: 'Id único de cada registro',
	)]
	public ?int $id;

	#[OField(
	  comment: 'Año del registro',
		nullable: false,
	)]
	public ?int $year;

	#[OField(
	  comment: 'Mes del registro',
		nullable: false,
	)]
	public ?int $month;

	#[OField(
	  comment: 'Día del registro',
		nullable: false,
	)]
	public ?int $day;

	#[OField(
	  comment: 'Total PUC del almacén',
	  nullable: false,
	  default: 0
	)]
	public ?float $total_puc;

	#[OField(
	  comment: 'Total PVP del almacén',
	  nullable: false,
	  default: 0
	)]
	public ?float $total_pvp;

	#[OField(
	  comment: 'Media de margen del almacén',
	  nullable: false,
	  default: 0
	)]
	public ?float $media_margen;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
