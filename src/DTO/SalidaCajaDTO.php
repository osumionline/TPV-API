<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class SalidaCajaDTO extends ODTO{
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public string $concepto = '';

	#[ODTOField(required: false)]
	public ?string $descripcion = null;

	#[ODTOField(required: false)]
	public ?float $importe = null;
}
