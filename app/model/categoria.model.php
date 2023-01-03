<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Categoria extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada categoría'
			),
			new OModelField(
				name: 'id_padre',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Id de la categoría padre en caso de ser una subcategoría'
			),
			new OModelField(
				name: 'nombre',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 50,
				comment: 'Nombre de la categoría'
			),
			new OModelField(
				name: 'created_at',
				type: OMODEL_CREATED,
				comment: 'Fecha de creación del registro'
			),
			new OModelField(
				name: 'updated_at',
				type: OMODEL_UPDATED,
				nullable: true,
				default: null,
				comment: 'Fecha de última modificación del registro'
			)
		);

		parent::load($model);
	}

	private ?Categoria $padre = null;

	/**
	 * Obtiene la categoría padre de la actual categoría
	 *
	 * @return Categoria Categoría padre de la actual categoría
	 */
	public function getPadre(): ?Categoria {
		if ($this->get('id_padre') != 0 && is_null($this->padre)) {
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
		$c = new Categoria();
		$c->find(['id' => $this->get('id_padre')]);
		$this->setPadre($c);
	}

	/**
	 * Función para borrar una categoría y quitarse de los artículos que la tuviesen asignada
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();
		$sql = "UPDATE `articulo` SET `id_categoria` = NULL WHERE `id_categoria` = ?";
		$db->query($sql, [$this->get('id')]);

		$this->delete();
	}
}
