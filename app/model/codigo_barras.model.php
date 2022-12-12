<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;

class CodigoBarras extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único para cada código de barras'
			),
			new OModelField(
				name: 'id_articulo',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'articulo.id',
				comment: 'Id del artículo al que pertenece el código de barras'
			),
			new OModelField(
				name: 'codigo_barras',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Código de barras del artículo'
			),
			new OModelField(
				name: 'por_defecto',
				type: OMODEL_BOOL,
				comment: 'Indica si es el código de barras asignado por defecto por el TPV 1 o añadido a mano 1'
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
		$a = new Articulo();
		$a->find(['id' => $this->get('id_articulo')]);
		$this->setArticulo($a);
	}
}
