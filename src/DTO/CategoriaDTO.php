<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class CategoriaDTO extends ODTO {
	#[ODTOField(required: true)]
	public ?int $id = null;

  #[ODTOField]
	public ?int $idPadre = null;

	#[ODTOField(required: true)]
	public ?string $nombre = null;
}
