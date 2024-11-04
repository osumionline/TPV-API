<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Articulo;

class LineaVenta extends OModel {
	#[OPK(
	  comment: 'Id única de cada línea de venta'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id de la venta a la que pertenece la línea',
	  nullable: false,
	  ref: 'venta.id',
	  default: null
	)]
	public ?int $id_venta;

	#[OField(
	  comment: 'Id del artículo que está siendo vendido',
	  nullable: true,
	  ref: 'articulo.id',
	  default: null
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
	  comment: 'Precio Unitario de Compra del artículo en el momento de su venta',
	  nullable: false,
	  default: 0
	)]
	public ?float $puc;

	#[OField(
	  comment: 'Precio de Venta al Público del artículo en el momento de su venta',
	  nullable: false,
	  default: 0
	)]
	public ?float $pvp;

	#[OField(
	  comment: 'IVA del artículo en el momento de su venta',
	  nullable: false,
	  default: 0
	)]
	public ?int $iva;

	#[OField(
	  comment: 'Importe total de la línea',
	  nullable: false,
	  default: 0
	)]
	public ?float $importe;

	#[OField(
	  comment: 'Porcentaje de descuento aplicado',
	  nullable: true,
	  default: null
	)]
	public ?int $descuento;

	#[OField(
	  comment: 'Importe directo en descuento',
	  nullable: true,
	  default: null
	)]
	public ?float $importe_descuento;

	#[OField(
	  comment: 'Cantidad de artículos devueltos',
	  nullable: false,
	  default: 0
	)]
	public ?int $devuelto;

	#[OField(
	  comment: 'Cantidad de artículos vendidos',
	  nullable: false,
	  default: 0
	)]
	public ?int $unidades;

	#[OField(
	  comment: 'Indica si línea es un regalo',
	  nullable: false,
	  default: false
	)]
	public ?bool $regalo;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	/**
	 * Obtiene el beneficio de la venta
	 *
	 * @return float Beneficio de la venta
	 */
	public function getBeneficio(): float {
		return $this->importe - ($this->unidades * $this->puc);
	}

	/**
	 * Obtiene el importe total descontado en la línea
	 *
	 * @return float Importe descontado
	 */
	public function getTotalDescuento(): float {
		if (!is_null($this->descuento) && $this->descuento !== 0) {
		  return $this->unidades * $this->pvp * ($this->descuento / 100);
		}
		if (!is_null($this->importe_descuento) && $this->importe_descuento !== 0) {
		  return $this->unidades * $this->importe_descuento;
		}
		return 0;
	}

	private ?Venta $venta = null;

	/**
	 * Obtiene la venta a la que pertenece la línea
	 *
	 * @return Venta Venta a la que pertenece la línea
	 */
	public function getVenta(): Venta {
		if (is_null($this->venta)) {
			$this->loadVenta();
		}
		return $this->venta;
	}

	/**
	 * Guarda la venta a la que pertenece la línea
	 *
	 * @param Venta $v Venta a la que pertenece la línea
	 *
	 * @return void
	 */
	public function setVenta(Venta $v): void {
		$this->venta = $v;
	}

	/**
	 * Carga la venta a la que pertenece la línea
	 *
	 * @return void
	 */
	public function loadVenta(): void {
		$this->setVenta(Venta::findOne(['id' => $this->id_venta]));
	}

	private ?Articulo $articulo = null;

	/**
	 * Obtiene el artículo al que pertenece la línea
	 *
	 * @return Articulo Artículo al que pertenece la línea
	 */
	public function getArticulo(): ?Articulo {
		if (is_null($this->id_articulo)) {
			return null;
		}
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
