<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;

/*
ventas:
  empleado que ha hecho la venta
articulos:
  articulo creado: empleado que ha creado el articulo, marca, proveedor, stock, pvp
  articulo modificado: empleado, marca, proveedor, stock, pvp (valores originales y nuevos)
*/

class EventLog extends OModel {
	#[OPK(
	  comment: 'Id único para cada empleado'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre del empleado',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Contraseña cifrada del empleado',
	  nullable: true,
	  max: 200,
	  default: null
	)]
	public ?string $pass;

	#[OField(
	  comment: 'Código de color hexadecimal para distinguir a cada empleado',
	  nullable: false,
	  max: 6,
	  default: null
	)]
	public ?string $color;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado del cliente',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;
}
