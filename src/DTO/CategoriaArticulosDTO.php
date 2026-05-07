<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class CategoriaArticulosDTO extends ODTO {
	#[ODTOField(required: true)]
	public ?int $idCategoria = null;

	#[ODTOField(required: true)]
	public ?array $idArticulos = null;
}
