<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\Foto;
use Osumi\OsumiFramework\App\Model\Marca;
use Osumi\OsumiFramework\App\Model\Categoria;
use Osumi\OsumiFramework\App\Model\Proveedor;
use Osumi\OsumiFramework\App\Model\Etiqueta;
use Osumi\OsumiFramework\App\Model\EtiquetaWeb;

class Articulo extends OModel {
	#[OPK(
	  comment: 'Id único de cada artículo'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Localizador único de cada artículo',
	  nullable: false,
	  default: null
	)]
	public ?int $localizador;

	#[OField(
	  comment: 'Nombre del artículo',
	  nullable: false,
	  max: 100,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Slug del nombre del artículo',
	  nullable: false,
	  max: 100,
	  default: null
	)]
	public ?string $slug;

	#[OField(
	  comment: 'Id de la categoría en la que se engloba el artículo',
	  nullable: true,
	  default: null
	)]
	public ?int $id_categoria;

	#[OField(
	  comment: 'Id de la marca del artículo',
	  nullable: false,
	  ref: 'marca.id'
	)]
	public ?int $id_marca;

	#[OField(
	  comment: 'Id del proveedor del artículo',
	  nullable: true,
	  ref: 'proveedor.id',
	  default: null
	)]
	public ?int $id_proveedor;

	#[OField(
	  comment: 'Referencia original del proveedor',
	  nullable: true,
	  max: 50,
	  default: null
	)]
	public ?string $referencia;

	#[OField(
	  comment: 'Precio del artículo en el albarán',
	  nullable: false,
	  default: 0
	)]
	public ?float $palb;

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

	#[OField(
	  comment: 'PVP del artículo con descuento',
	  nullable: true,
	  default: null
	)]
	public ?float $pvp_descuento;

	#[OField(
	  comment: 'IVA del artículo',
	  nullable: false,
	  default: null
	)]
	public ?int $iva;

	#[OField(
	  comment: 'Recargo de equivalencia',
	  nullable: false,
	  default: null
	)]
	public ?float $re;

	#[OField(
	  comment: 'Margen de beneficio del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?float $margen;

	#[OField(
	  comment: 'Margen de beneficio del artículo con descuento',
	  nullable: true,
	  default: null
	)]
	public ?float $margen_descuento;

	#[OField(
	  comment: 'Stock actual del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?int $stock;

	#[OField(
	  comment: 'Stock mínimo del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?int $stock_min;

	#[OField(
	  comment: 'Stock máximo del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?int $stock_max;

	#[OField(
	  comment: 'Lote óptimo para realizar pedidos del artículo',
	  nullable: false,
	  default: 0
	)]
	public ?int $lote_optimo;

	#[OField(
	  comment: 'Indica si el producto está disponible desde la web 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $venta_online;

	#[OField(
	  comment: 'Fecha de caducidad del artículo',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $fecha_caducidad;

	#[OField(
	  comment: 'Indica si debe ser mostrado en la web 1 o no 0',
	  nullable: false,
	  default: false
	)]
	public ?bool $mostrar_en_web;

	#[OField(
	  comment: 'Descripción corta para la web',
	  nullable: true,
	  max: 250,
	  default: null
	)]
	public ?string $desc_corta;

	#[OField(
	  comment: 'Descripción larga para la web',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $descripcion;

	#[OField(
	  comment: 'Observaciones o notas sobre el artículo',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $observaciones;

	#[OField(
	  comment: 'Mostrar observaciones en pedidos 0 no 1 si',
	  nullable: false,
	  default: false
	)]
	public ?bool $mostrar_obs_pedidos;

	#[OField(
	  comment: 'Mostrar observaciones en ventas 0 no 1 si',
	  nullable: false,
	  default: false
	)]
	public ?bool $mostrar_obs_ventas;

	#[OField(
	  comment: 'Acceso directo al artículo',
	  nullable: true,
	  default: null
	)]
	public ?int $acceso_directo;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado del artículo',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

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
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = CodigoBarras::from($res);
		}

		$this->setCodigosBarras($list);
	}

	/**
	 * Función para obtener la lista de códigos de barras que no son el de por defecto.
	 *
	 * @return array Lista de códigos de barras
	 */
	public function getNotDefaultCodigosBarras(): array {
		$ret = [];
		$codigos_barras = $this->getCodigosBarras();

		foreach ($codigos_barras as $cb) {
			if (!$cb->por_defecto) {
				$ret[] = $cb;
			}
		}

		return $ret;
	}

	/**
	 * Función para saber si un artículo tiene algún código de barras distinto del por defecto
	 *
	 * @return bool Indica si tiene o no un código de barras distinto del por defecto
	 */
	public function hasCodigoBarras(): bool {
		foreach ($this->getCodigosBarras() as $cb) {
      if (!$cb->por_defecto) {
        return true;
      }
    }
    return false;
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
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = Foto::from($res);
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
			$ret[] = $foto->id;
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
		$this->setMarca(Marca::findOne(['id' => $this->id_marca]));
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
		$this->setCategoria(Categoria::findOne(['id' => $this->id_categoria]));
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
		if (!is_null($this->proveedor->deleted_at)){
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
		$this->setProveedor(Proveedor::findOne(['id' => $this->id_proveedor]));
	}

	private ?array $etiquetas = null;

	/**
	 * Obtiene el listado de etiquetas de un artículo
	 *
	 * @return array Listado de etiquetas
	 */
	public function getEtiquetas(): array {
		if (is_null($this->etiquetas)) {
			$this->loadEtiquetas();
		}
		return $this->etiquetas;
	}

	/**
	 * Guarda la lista de etiquetas
	 *
	 * @param array $e Lista de etiquetas
	 *
	 * @return void
	 */
	public function setEtiquetas(array $e): void {
		$this->etiquetas = $e;
	}

	/**
	 * Carga la lista de etiquetas de un artículo
	 *
	 * @return void
	 */
	public function loadEtiquetas(): void {
		$db = new ODB();
		$sql = "SELECT e.* FROM `etiqueta` e, `articulo_etiqueta` ae WHERE e.`id` = ae.`id_etiqueta` AND ae.`id_articulo` = ?";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res=$db->next()) {
			$list[] = Etiqueta::from($res);
		}

		$this->setEtiquetas($list);
	}

	private ?array $etiquetas_web = null;

	/**
	 * Obtiene el listado de etiquetas web de un artículo
	 *
	 * @return array Listado de etiquetas web
	 */
	public function getEtiquetasWeb(): array {
		if (is_null($this->etiquetas_web)) {
			$this->loadEtiquetasWeb();
		}
		return $this->etiquetas_web;
	}

	/**
	 * Guarda la lista de etiquetas web
	 *
	 * @param array $e Lista de etiquetas web
	 *
	 * @return void
	 */
	public function setEtiquetasWeb(array $e): void {
		$this->etiquetas_web = $e;
	}

	/**
	 * Carga la lista de etiquetas web de un artículo
	 *
	 * @return void
	 */
	public function loadEtiquetasWeb(): void {
		$db = new ODB();
		$sql = "SELECT e.* FROM `etiqueta_web` e, `articulo_etiqueta_web` ae WHERE e.`id` = ae.`id_etiqueta_web` AND ae.`id_articulo` = ?";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res=$db->next()) {
			$list[] = EtiquetaWeb::from($res);
		}

		$this->setEtiquetasWeb($list);
	}

	/**
	 * Función para borrar completamente un artículo y sus relaciones
	 *
	 * @return void
	 */
	public function deleteFull(): void {
		$db = new ODB();

		// Fotos
		$fotos = $this->getFotos();
		foreach ($fotos as $foto) {
			$foto->deleteFull();
		}

		$sql = "DELETE * FROM `articulo_foto` WHERE `id_articulo` = ?";
		$db->query($sql, [$this->id]);

		// Etiquetas
		$etiquetas = $this->getEtiquetas();
		$sql = "DELETE FROM `articulo_etiqueta` WHERE `id_articulo` = ?";
		$db->query($sql, [$this->id]);

		foreach ($etiquetas as $etiqueta) {
			$num = ArticuloEtiqueta::count(['id_etiqueta' => $etiqueta->id]);
			if ($num === 0) {
				$etiqueta->delete();
			}
		}

		// Etiquetas web
		$etiquetas_web = $this->getEtiquetasWeb();
		$sql = "DELETE FROM `articulo_etiqueta_web` WHERE `id_articulo` = ?";
		$db->query($sql, [$this->id]);

		foreach ($etiquetas_web as $etiqueta_web) {
			$num = ArticuloEtiquetaWeb::count(['id_etiqueta_web' => $etiqueta_web->id]);
			if ($num === 0) {
				$etiqueta_web->delete();
			}
		}

		// Códigos de barras
		$sql = "DELETE FROM `codigo_barras` WHERE `id_articulo` = ?";
		$db->query($sql, [$this->id]);

		// Finalmente, borro el artículo
		$this->delete();
	}
}
