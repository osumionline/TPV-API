<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class MarcaDTO extends ODTO{
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public string $nombre = '';

	#[ODTOField(required: false)]
	public ?int $idFoto = null;

	#[ODTOField(required: false)]
	public ?string $foto = null;

	#[ODTOField(required: false)]
	public ?string $direccion = '';

	#[ODTOField(required: false)]
	public ?string $telefono = '';

	#[ODTOField(required: false)]
	public ?string $email = '';

	#[ODTOField(required: false)]
	public ?string $web = '';

	#[ODTOField(required: false)]
	public ?string $observaciones = '';

	#[ODTOField(required: false)]
	public ?bool $crearProveedor = false;
}
