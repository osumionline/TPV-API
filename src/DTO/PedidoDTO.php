<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class PedidoDTO extends ODTO{
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: false)]
	public ?int $idProveedor = null;

	#[ODTOField(required: false)]
	public ?int $idMetodoPago = null;

	#[ODTOField(required: false)]
	public ?bool $re = null;

	#[ODTOField(required: false)]
	public ?bool $ue = null;

	#[ODTOField(required: true)]
	public ?string $tipo = null;

	#[ODTOField(required: false)]
	public ?string $num = null;

	#[ODTOField(required: true)]
	public ?string $fechaPago = null;

	#[ODTOField(required: true)]
	public ?string $fechaPedido = null;

	#[ODTOField(required: true)]
	public ?array $lineas = null;

	#[ODTOField(required: false)]
	public ?float $importe = null;

	#[ODTOField(required: false)]
	public ?float $portes = null;

	#[ODTOField(required: false)]
	public ?int $descuento = null;

	#[ODTOField(required: false)]
	public ?bool $faltas = null;

	#[ODTOField(required: true)]
	public ?bool $recepcionado = null;

	#[ODTOField(required: false)]
	public ?string $observaciones = null;

	#[ODTOField(required: false)]
	public ?array $pdfs = null;

	#[ODTOField(required: false)]
	public ?array $vista = null;
}
