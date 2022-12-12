<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\OModelGroup;
use OsumiFramework\OFW\DB\OModelField;
use OsumiFramework\OFW\DB\ODB;

class Articulo extends OModel {
	function __construct() {
		$model = new OModelGroup(
			new OModelField(
				name: 'id',
				type: OMODEL_PK,
				comment: 'Id único de cada artículo'
			),
			new OModelField(
				name: 'localizador',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Localizador único de cada artículo'
			),
			new OModelField(
				name: 'nombre',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 100,
				comment: 'Nombre del artículo'
			),
			new OModelField(
				name: 'slug',
				type: OMODEL_TEXT,
				nullable: false,
				default: null,
				size: 100,
				comment: 'Slug del nombre del artículo'
			),
			new OModelField(
				name: 'id_categoria',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'Id de la categoría en la que se engloba el artículo'
			),
			new OModelField(
				name: 'id_marca',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'marca.id',
				comment: 'Id de la marca del artículo'
			),
			new OModelField(
				name: 'id_proveedor',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				ref: 'proveedor.id',
				comment: 'Id del proveedor del artículo'
			),
			new OModelField(
				name: 'referencia',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 50,
				comment: 'Referencia original del proveedor'
			),
			new OModelField(
				name: 'palb',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Precio del artículo en el albarán'
			),
			new OModelField(
				name: 'puc',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Precio Unitario de Compra del artículo'
			),
			new OModelField(
				name: 'pvp',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Precio de Venta al Público del artículo'
			),
			new OModelField(
				name: 'iva',
				type: OMODEL_NUM,
				nullable: false,
				default: null,
				comment: 'IVA del artículo'
			),
			new OModelField(
				name: 're',
				type: OMODEL_FLOAT,
				nullable: false,
				default: null,
				comment: 'Recargo de equivalencia'
			),
			new OModelField(
				name: 'margen',
				type: OMODEL_FLOAT,
				nullable: false,
				default: 0,
				comment: 'Margen de beneficio del artículo'
			),
			new OModelField(
				name: 'stock',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock actual del artículo'
			),
			new OModelField(
				name: 'stock_min',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock mínimo del artículo'
			),
			new OModelField(
				name: 'stock_max',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Stock máximo del artículo'
			),
			new OModelField(
				name: 'lote_optimo',
				type: OMODEL_NUM,
				nullable: false,
				default: 0,
				comment: 'Lote óptimo para realizar pedidos del artículo'
			),
			new OModelField(
				name: 'venta_online',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si el producto está disponible desde la web 1 o no 0'
			),
			new OModelField(
				name: 'fecha_caducidad',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de caducidad del artículo'
			),
			new OModelField(
				name: 'mostrar_en_web',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Indica si debe ser mostrado en la web 1 o no 0'
			),
			new OModelField(
				name: 'desc_corta',
				type: OMODEL_TEXT,
				nullable: true,
				default: null,
				size: 250,
				comment: 'Descripción corta para la web'
			),
			new OModelField(
				name: 'descripcion',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Descripción larga para la web'
			),
			new OModelField(
				name: 'observaciones',
				type: OMODEL_LONGTEXT,
				nullable: true,
				default: null,
				comment: 'Observaciones o notas sobre el artículo'
			),
			new OModelField(
				name: 'mostrar_obs_pedidos',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Mostrar observaciones en pedidos 0 no 1 si'
			),
			new OModelField(
				name: 'mostrar_obs_ventas',
				type: OMODEL_BOOL,
				nullable: false,
				default: false,
				comment: 'Mostrar observaciones en ventas 0 no 1 si'
			),
			new OModelField(
				name: 'acceso_directo',
				type: OMODEL_NUM,
				nullable: true,
				default: null,
				comment: 'Acceso directo al artículo'
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
			),
			new OModelField(
				name: 'deleted_at',
				type: OMODEL_DATE,
				nullable: true,
				default: null,
				comment: 'Fecha de borrado del artículo'
			)
		);

		parent::load($model);
	}

	private ?array $codigos_barras = null;

	/**
	 * Obtiene el listado de códigos de barras de un artículo
	 *
	 * @return array Listado de códigos de barras
	 */
	public function getCodigosBarras(): array {
		if (is_null($this->codigos_barras)) {
			$this->loadCodigosBarras();
		}
		return $this->codigos_barras;
	}

	/**
	 * Guarda la lista de códigos de barras
	 *
	 * @param array $cb Lista de códigos de barras
	 *
	 * @return void
	 */
	public function setCodigosBarras(array $cb): void {
		$this->codigos_barras = $cb;
	}

	/**
	 * Carga la lista de códigos de barras de un artículo
	 *
	 * @return void
	 */
	public function loadCodigosBarras(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `codigo_barras` WHERE `id_articulo` = ? ORDER BY `por_defecto` DESC, `created_at` ASC";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$cb = new CodigoBarras();
			$cb->update($res);
			array_push($list, $cb);
		}

		$this->setCodigosBarras($list);
	}

	private ?array $fotos = null;

	/**
	 * Obtiene el listado de fotos de un artículo
	 *
	 * @return array Listado de fotos
	 */
	public function getFotos(): array {
		if (is_null($this->fotos)) {
			$this->loadFotos();
		}
		return $this->fotos;
	}

	/**
	 * Guarda la lista de fotos
	 *
	 * @param array $f Lista de fotos
	 *
	 * @return void
	 */
	public function setFotos(array $f): void {
		$this->fotos = $f;
	}

	/**
	 * Carga la lista de fotos de un artículo
	 *
	 * @return void
	 */
	public function loadFotos(): void {
		$db = new ODB();
		$sql = "SELECT f.* FROM `foto` f, `articulo_foto` af WHERE f.`id` = af.`id_foto` AND af.`id_articulo` = ? ORDER BY af.`orden`";
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$f = new Foto();
			$f->update($res);
			array_push($list, $f);
		}

		$this->setFotos($list);
	}

	/**
	 * Obtiene la lista de ids de las fotos del artículo
	 *
	 * @return array Lista de ids de las fotos
	 */
	public function getFotosList(): array {
		$list = $this->getFotos();
		$ret = [];

		foreach ($list as $foto) {
			array_push($ret, $foto->get('id'));
		}

		return $ret;
	}

	private ?Marca $marca = null;

	/**
	 * Obtiene la marca a la que pertenece el artículo
	 *
	 * @return Marca Marca a la que pertenece el artículo, a no ser que se haya borrado
	 */
	public function getMarca(): ?Marca {
		if (is_null($this->marca)) {
			$this->loadMarca();
		}
		if (!is_null($this->marca->get('deleted_at'))){
			return null;
		}
		return $this->marca;
	}

	/**
	 * Guarda la marca a la que pertenece el artículo
	 *
	 * @param Marca $m Marca a la que pertenece el artículo
	 *
	 * @return void
	 */
	public function setMarca(Marca $m): void {
		$this->marca = $m;
	}

	/**
	 * Carga la marca a la que pertenece el artículo
	 *
	 * @return void
	 */
	public function loadMarca(): void {
		$m = new Marca();
		$m->find(['id' => $this->get('id_marca')]);
		$this->setMarca($m);
	}

	private ?Categoria $categoria = null;

	/**
	 * Obtiene la categoría a la que pertenece el artículo
	 *
	 * @return Categoria Categoria a la que pertenece el artículo
	 */
	public function getCategoria(): Categoria {
		if (is_null($this->categoria)) {
			$this->loadCategoria();
		}
		return $this->categoria;
	}

	 /**
	 * Guarda la categoría a la que pertenece el artículo
	 *
	 * @param Categoria $c Categoría a la que pertenece el artículo
	 *
	 * @return void
	 */
	public function setCategoria(Categoria $c): void {
		$this->categoria = $c;
	}

	/**
	 * Carga la categoría a la que pertenece el artículo
	 *
	 * @return void
	 */
	public function loadCategoria(): void {
		$c = new Categoria();
		$c->find(['id' => $this->get('id_categoria')]);
		$this->setCategoria($c);
	}

	private ?Proveedor $proveedor = null;

	/**
	 * Obtiene el proveedor al que pertenece el artículo
	 *
	 * @return Proveedor Proveedor al que pertenece el artículo, a no ser que se haya borrado
	 */
	public function getProveedor(): ?Proveedor {
		if (is_null($this->proveedor)) {
			$this->loadProveedor();
		}
		if (is_null($this->proveedor->get('deleted_at'))){
			return null;
		}
		return $this->proveedor;
	}

	/**
	 * Guarda el proveedor al que pertenece el artículo
	 *
	 * @param Proveedor $p Proveedor al que pertenece el artículo
	 *
	 * @return void
	 */
	public function setProveedor(Proveedor $p): void {
		$this->proveedor = $p;
	}

	/**
	 * Carga el proveedor al que pertenece el artículo
	 *
	 * @return void
	 */
	public function loadProveedor(): void {
		$p = new Proveedor();
		$p->find(['id' => $this->get('id_proveedor')]);
		$this->setProveedor($p);
	}
}
