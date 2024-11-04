<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;

class Categoria extends OModel {
	#[OPK(
	  comment: 'Id único para cada categoría'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Id de la categoría padre en caso de ser una subcategoría',
	  nullable: true,
	  default: null
	)]
	public ?int $id_padre;

	#[OField(
	  comment: 'Nombre de la categoría',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	private ?Categoria $padre = null;

	/**
	 * Obtiene la categoría padre de la actual categoría
	 *
	 * @return Categoria Categoría padre de la actual categoría
	 */
	public function getPadre(): ?Categoria {
		if ($this->id_padre != 0 && is_null($this->padre)) {
			$this->loadPadre();
		}
		return $this->padre;
	}

	/**
	 * Guarda la categoría padre de la actual categoría
	 *
	 * @param Categoria $c Categoría padre de la actual categoría
	 *
	 * @return void
	 */
	public function setPadre(Categoria $c): void {
		$this->padre = $c;
	}

	/**
	 * Carga la categoría padre de la actual categoría
	 *
	 * @return void
	 */
	public function loadPadre(): void {
		$this->setPadre(Categoria::findOne(['id' => $this->id_padre]));
	}

	/**
	 * Función para borrar una categoría y quitarse de los artículos que la tuviesen asignada
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();
		$sql = "UPDATE `articulo` SET `id_categoria` = NULL WHERE `id_categoria` = ?";
		$db->query($sql, [$this->id]);

		$this->delete();
	}
}
