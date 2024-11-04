<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\App\Model\Reserva;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\Venta;

class LineaReserva extends OModel {
	#[OPK(
	  comment: 'Id única de cada línea de reserva'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id de la reserva a la que pertenece la línea',
	  nullable: false,
	  ref: 'reserva.id',
	  default: null
	)]
	public ?int $id_reserva;

	#[OField(
	  comment: 'Id del artículo que está siendo reservado',
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
	  comment: 'Cantidad de artículos vendidos',
	  nullable: false,
	  default: 0
	)]
	public ?int $unidades;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	private ?Reserva $reserva = null;

	/**
	 * Obtiene la reserva a la que pertenece la línea
	 *
	 * @return Reserva Reserva a la que pertenece la línea
	 */
	public function getReserva(): Reserva {
		if (is_null($this->reserva)) {
			$this->loadReserva();
		}
		return $this->reserva;
	}

	/**
	 * Guarda la reserva a la que pertenece la línea
	 *
	 * @param Reserva $r Reserva a la que pertenece la línea
	 *
	 * @return void
	 */
	public function setReserva(Reserva $r): void {
		$this->reserva = $r;
	}

	/**
	 * Carga la reserva a la que pertenece la línea
	 *
	 * @return void
	 */
	public function loadReserva(): void {
		$this->setReserva(Reserva::findOne(['id' => $this->id_reserva]));
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
