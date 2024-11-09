<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class VistaPedido extends OModel {
	#[OPK(
	  comment: 'Id del pedido',
	  ref: 'pedido.id'
	)]
	public ?int $id_pedido;

	#[OPK(
	  comment: 'Id de la columna a mostrar/ocultar',
	  nullable: false
	)]
	public ?int $id_column;

	#[OField(
	  comment: 'Indica si la columna se tiene que mostrar 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $status;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;
}
