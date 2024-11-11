<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;

class Caducidad extends OModel {
	#[OPK(
		comment: 'Id único de cada caducidad'
	)]
	public ?int $id;

	#[OField(
		comment: 'Id del artículo',
		nullable: false,
		ref: 'articulo.id'
	)]
	public ?int $id_articulo;

	#[OField(
		comment: 'Número de unidades',
		nullable: false
	)]
	public ?int $unidades;

	#[OField(
	  comment: 'Precio Unitario de Compra del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $puc;

	#[OField(
	  comment: 'Precio de Venta al Público del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $pvp;

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
	 * Guarda artículo
	 *
	 * @param Articulo $articulo Articulo a guardar
	 *
	 * @return void
	 */
	public function setArticulo(Articulo $articulo): void {
		$this->articulo = $articulo;
	}

	/**
	 * Obtiene artículo
	 *
	 * @return Articulo Artículo a obtener
	 */
	public function getArticulo(): Articulo {
		if (is_null($this->articulo)) {
			$this->loadArticulo();
		}
		return $this->articulo;
	}

	/**
	 * Carga artículo
	 *
	 * @return void
	 */
	private function loadArticulo(): void {
		$this->articulo = Articulo::findOne(['id_articulo' => $this->id]);
	}
}
