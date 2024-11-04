<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\App\Model\Articulo;

class LineaPedido extends OModel {
	#[OPK(
	  comment: 'Id único para cada línea de un pedido'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del pedido al que pertenece la línea',
	  nullable: false,
	  ref: 'pedido.id'
	)]
	public ?int $id_pedido;

	#[OField(
	  comment: 'Id del artículo recibido',
	  nullable: false,
	  ref: 'articulo.id'
	)]
	public ?int $id_articulo;

	#[OField(
	  comment: 'Nombre del artículo',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $nombre_articulo;

	#[OField(
	  comment: 'Nuevo código de barras para el artículo',
	  nullable: true,
	  max: 20,
	  default: null
	)]
	public ?string $codigo_barras;

	#[OField(
	  comment: 'Número de unidades recibidas',
	  nullable: false,
	  default: 0
	)]
	public ?int $unidades;

	#[OField(
	  comment: 'Precio de albarán del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $palb;

	#[OField(
	  comment: 'PUC del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $puc;

	#[OField(
	  comment: 'PVP del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $pvp;

	#[OField(
	  comment: 'Porcentaje de margen del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $margen;

	#[OField(
	  comment: 'IVA del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $iva;

	#[OField(
	  comment: 'RE del artículo',
	  nullable: true,
	  default: null
	)]
	public ?float $re;

	#[OField(
	  comment: 'Porcentaje de descuento del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $descuento;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	private ?Articulo $articulo = null;

	/**
	 * Obtiene el artículo al que pertenece la línea
	 *
	 * @return Articulo Artículo al que pertenece la línea
	 */
	public function getArticulo(): Articulo {
		if (is_null($this->articulo)) {
			$this->loadArticulo();
		}
		return $this->articulo;
	}

	/**
	 * Guarda el artículo al que pertenece la línea
	 *
	 * @param Articulo $a Artículo al que pertenece la línea
	 *
	 * @return void
	 */
	public function setArticulo(Articulo $a): void {
		$this->articulo = $a;
	}

	/**
	 * Carga el artículo al que pertenece la línea
	 *
	 * @return void
	 */
	public function loadArticulo(): void {
		$this->setArticulo(Articulo::findOne(['id' => $this->id_articulo]));
	}
}
