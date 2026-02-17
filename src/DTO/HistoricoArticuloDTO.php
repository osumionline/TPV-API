<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class HistoricoArticuloDTO extends ODTO {
	#[ODTOField(required: true)]
	public ?int $id = null;

	#[ODTOField(required: false)]
	public ?string $orderBy = null;

	#[ODTOField(required: false)]
	public ?string $orderSent = null;

	#[ODTOField(required: true)]
	public ?int $pagina = null;

	#[ODTOField(required: true)]
	public ?int $num = null;
}
