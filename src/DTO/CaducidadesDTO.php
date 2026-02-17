<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class CaducidadesDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $year = null;

	#[ODTOField(required: false)]
	public ?int $month = null;

	#[ODTOField(required: true)]
	public ?int $pagina = null;

	#[ODTOField(required: true)]
	public ?int $num = null;

	#[ODTOField(required: false)]
	public ?int $idMarca = null;

	#[ODTOField(required: false)]
	public ?string $nombre = null;

	#[ODTOField(required: false)]
	public ?string $orderBy = null;

	#[ODTOField(required: false)]
	public ?string $orderSent = null;
}
