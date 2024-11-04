<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class EmpleadoRol extends OModel {
	#[OPK(
	  comment: 'Id del empleado',
	  ref: 'empleado.id'
	)]
	public ?int $id_empleado;

	#[OPK(
	  comment: 'Id del permiso que se le otorga al empleado',
	  nullable: false
	)]
	public ?int $id_rol;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
