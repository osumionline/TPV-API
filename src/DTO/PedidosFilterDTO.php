<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class PedidosFilterDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?string $fechaDesde = null;

	#[ODTOField(required: false)]
	public ?string $fechaHasta = null;

	#[ODTOField(required: false)]
	public ?int $idProveedor = null;

	#[ODTOField(required: false)]
	public ?string $albaran = null;

	#[ODTOField(required: false)]
	public ?float $importeDesde = null;

	#[ODTOField(required: false)]
	public ?float $importeHasta = null;

	#[ODTOField(required: true)]
	public ?int $pagina = null;

	#[ODTOField(required: true)]
	public ?int $num = null;
}
