<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class CierreCajaDTO extends ODTO {
	#[ODTOField(required: true)]
	public ?string $date = null;

	#[ODTOField(required: false)]
	public ?float $saldoInicial = null;

	#[ODTOField(required: false)]
	public ?float $importeEfectivo = null;

	#[ODTOField(required: false)]
	public ?float $salidasCaja = null;

	#[ODTOField(required: false)]
	public ?float $saldoFinal = null;

	#[ODTOField(required: true)]
	public ?float $real = null;

	#[ODTOField(required: false)]
	public ?int $importe1c = null;

	#[ODTOField(required: false)]
	public ?int $importe2c = null;

	#[ODTOField(required: false)]
	public ?int $importe5c = null;

	#[ODTOField(required: false)]
	public ?int $importe10c = null;

	#[ODTOField(required: false)]
	public ?int $importe20c = null;

	#[ODTOField(required: false)]
	public ?int $importe50c = null;

	#[ODTOField(required: false)]
	public ?int $importe1 = null;

	#[ODTOField(required: false)]
	public ?int $importe2 = null;

	#[ODTOField(required: false)]
	public ?int $importe5 = null;

	#[ODTOField(required: false)]
	public ?int $importe10 = null;

	#[ODTOField(required: false)]
	public ?int $importe20 = null;

	#[ODTOField(required: false)]
	public ?int $importe50 = null;

	#[ODTOField(required: false)]
	public ?int $importe100 = null;

	#[ODTOField(required: false)]
	public ?int $importe200 = null;

	#[ODTOField(required: false)]
	public ?int $importe500 = null;

	#[ODTOField(required: false)]
	public ?float $retirado = null;

	#[ODTOField(required: false)]
	public ?float $entrada = null;

	#[ODTOField(required: false)]
	public ?array $tipos = null;
}
