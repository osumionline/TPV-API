<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\App\Model\Articulo;

class CodigoBarras extends OModel {
	#[OPK(
	  comment: 'Id único para cada código de barras'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id del artículo al que pertenece el código de barras',
	  nullable: false,
	  ref: 'articulo.id'
	)]
	public ?int $id_articulo;

	#[OField(
	  comment: 'Código de barras del artículo',
	  nullable: false,
	  max: 20
	)]
	public ?string $codigo_barras;

	#[OField(
	  comment: 'Indica si es el código de barras asignado por defecto por el TPV 1 o añadido a mano 1'
	)]
	public ?bool $por_defecto;

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
	 * Obtiene el artículo al que pertenece el código de barras
	 *
	 * @return Articulo Artículo al que pertenece el código de barras
	 */
	public function getArticulo(): Articulo {
		if (is_null($this->articulo)) {
			$this->loadArticulo();
		}
		return $this->articulo;
	}

	/**
	 * Guarda el artículo al que pertenece el código de barras
	 *
	 * @param Articulo $p Artículo al que pertenece el código de barras
	 *
	 * @return void
	 */
	public function setArticulo(Articulo $a): void {
		$this->articulo = $a;
	}

	/**
	 * Carga el artículo al que pertenece el código de barras
	 *
	 * @return void
	 */
	public function loadArticulo(): void {
		$this->setArticulo(Articulo::findOne(['id' => $this->id_articulo]));
	}
}
