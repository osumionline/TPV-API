<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class InventarioItemDTO extends ODTO {
	#[ODTOField(required: true)]
	public ?int $id = null;

	#[ODTOField(required: false)]
	public ?int $localizador = null;

	#[ODTOField(required: false)]
	public ?string $marca = null;

	#[ODTOField(required: false)]
	public ?string $referencia = null;

	#[ODTOField(required: false)]
	public ?string $nombre = null;

	#[ODTOField(required: true)]
	public ?int $stock = null;

	#[ODTOField(required: false)]
	public ?float $puc = null;

	#[ODTOField(required: true)]
	public ?float $pvp = null;

	#[ODTOField(required: false)]
	public ?string $codigoBarras = null;
}
