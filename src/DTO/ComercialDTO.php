<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class ComercialDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: false)]
	public ?int $idProveedor = null;

	#[ODTOField(required: true)]
	public ?string $nombre = '';

	#[ODTOField(required: false)]
	public ?string $telefono = '';

	#[ODTOField(required: false)]
	public ?string $email = '';

	#[ODTOField(required: false)]
	public ?string $observaciones = '';
}
