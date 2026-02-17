<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\DTO;

use Osumi\OsumiFramework\DTO\ODTO;
use Osumi\OsumiFramework\DTO\ODTOField;

class ArticuloDTO extends ODTO {
	#[ODTOField(required: false)]
	public ?int $id = null;

	#[ODTOField(required: false)]
	public ?int $localizador = null;

	#[ODTOField(required: true)]
	public string $nombre = '';

	#[ODTOField(required: false)]
	public ?int $idCategoria = null;

	#[ODTOField(required: true)]
	public ?int $idMarca = null;

	#[ODTOField(required: false)]
	public ?int $idProveedor = null;

	#[ODTOField(required: false)]
	public string $referencia = '';

	#[ODTOField(required: false)]
	public float $palb = 0;

	#[ODTOField(required: true)]
	public float $puc = 0;

	#[ODTOField(required: true)]
	public float $pvp = 0;

	#[ODTOField(required: false)]
	public ?float $pvpDescuento = 0;

	#[ODTOField(required: true)]
	public int $iva = 0;

	#[ODTOField(required: false)]
	public float $re = 0;

	#[ODTOField(required: false)]
	public float $margen = 0;

	#[ODTOField(required: false)]
	public ?float $margenDescuento = 0;

	#[ODTOField(required: true)]
	public int $stock = 0;

	#[ODTOField(required: false)]
	public int $stockMin = 0;

	#[ODTOField(required: false)]
	public int $stockMax = 0;

	#[ODTOField(required: false)]
	public int $loteOptimo = 0;

	#[ODTOField(required: false)]
	public bool $ventaOnline = false;

	#[ODTOField(required: false)]
	public ?string $fechaCaducidad = null;

	#[ODTOField(required: false)]
	public bool $mostrarEnWeb = false;

	#[ODTOField(required: false)]
	public string $descCorta = '';

	#[ODTOField(required: false)]
	public string $descripcion = '';

	#[ODTOField(required: false)]
	public string $observaciones = '';

	#[ODTOField(required: false)]
	public bool $mostrarObsPedidos = false;

	#[ODTOField(required: false)]
	public bool $mostrarObsVentas = false;

	#[ODTOField(required: false)]
	public array $codigosBarras = [];

	#[ODTOField(required: false)]
	public array $fotosList = [];

	#[ODTOField(required: false)]
	public string $nombreStatus = 'ok';
}
