<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class VentaDTO extends ODTO{
	#[ODTOField(required: false)]
	public ?float $efectivo = null;

	#[ODTOField(required: false)]
	public ?float $cambio = null;

	#[ODTOField(required: false)]
	public ?float $tarjeta = null;

	#[ODTOField(required: false)]
	public ?int $idEmpleado = null;

	#[ODTOField(required: false)]
	public ?int $idTipoPago = null;

	#[ODTOField(required: false)]
	public ?int $idCliente = null;

	#[ODTOField(required: false)]
	public ?float $total = null;

	#[ODTOField(required: true)]
	public array $lineas = [];

	#[ODTOField(required: false)]
	public bool $pagoMixto = false;

	#[ODTOField(required: true)]
	public ?string $imprimir = null;

	#[ODTOField(required: false)]
	public ?string $email = null;
}
