<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class TipoPagoDTO extends ODTO{
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public string $nombre = '';

	#[ODTOField(required: false)]
	public ?string $foto = null;

	#[ODTOField(required: false)]
	public bool $afectaCaja = false;

	#[ODTOField(required: false)]
	public ?int $orden = null;

	#[ODTOField(required: false)]
	public bool $fisico = true;
}
