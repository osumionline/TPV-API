<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class InventarioDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $idProveedor = null;

	#[ODTOField(required: false)]
	public ?int $idMarca = null;

	#[ODTOField(required: false)]
	public ?string $nombre = null;

	#[ODTOField(required: false)]
	public ?bool $descuento = null;

	#[ODTOField(required: false)]
	public ?string $orderBy = null;

	#[ODTOField(required: false)]
	public ?string $orderSent = null;

	#[ODTOField(required: true)]
	public ?int $pagina = null;

	#[ODTOField(required: false)]
	public ?int $num = null;
}
