<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class EmpleadoDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public string $nombre = '';

	#[ODTOField(required: false)]
	public bool $hasPassword = false;

	#[ODTOField(required: false)]
	public ?string $password = null;

	#[ODTOField(required: false)]
	public ?string $confirmPassword = null;

	#[ODTOField(required: false)]
	public ?string $color = null;

	#[ODTOField(required: false)]
	public array $roles = [];
}
