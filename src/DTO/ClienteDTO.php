<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class ClienteDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: true)]
	public string $nombreApellidos = '';

	#[ODTOField(required: false)]
	public ?string $dniCif = '';

	#[ODTOField(required: false)]
	public ?string $telefono = null;

	#[ODTOField(required: false)]
	public ?string $email = null;

	#[ODTOField(required: false)]
	public ?string $direccion = null;

	#[ODTOField(required: false)]
	public ?string $codigoPostal = null;

	#[ODTOField(required: false)]
	public ?string $poblacion = null;

	#[ODTOField(required: false)]
	public ?int $provincia = null;

	#[ODTOField(required: false)]
	public bool $factIgual = true;

	#[ODTOField(required: false)]
	public ?string $factNombreApellidos = null;

	#[ODTOField(required: false)]
	public ?string $factDniCif = null;

	#[ODTOField(required: false)]
	public ?string $factTelefono = null;

	#[ODTOField(required: false)]
	public ?string $factEmail = null;

	#[ODTOField(required: false)]
	public ?string $factDireccion = null;

	#[ODTOField(required: false)]
	public ?string $factCodigoPostal = null;

	#[ODTOField(required: false)]
	public ?string $factPoblacion = null;

	#[ODTOField(required: false)]
	public ?int $factProvincia = null;

	#[ODTOField(required: false)]
	public ?string $observaciones = null;

	#[ODTOField(required: false)]
	public ?int $descuento = null;
}
