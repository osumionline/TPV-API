<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class AddCaducidadDTO extends ODTO {
	#[ODTOField(required: true)]
	public ?int $idArticulo = null;

	#[ODTOField(required: true)]
	public ?int $unidades = null;
}
