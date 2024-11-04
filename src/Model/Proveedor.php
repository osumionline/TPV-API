<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Model;

use Osumi\OsumiFramework\ORM\OModel;
use Osumi\OsumiFramework\ORM\OPK;
use Osumi\OsumiFramework\ORM\OField;
use Osumi\OsumiFramework\ORM\OCreatedAt;
use Osumi\OsumiFramework\ORM\OUpdatedAt;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Marca;
use Osumi\OsumiFramework\App\Model\Comercial;

class Proveedor extends OModel {
	#[OPK(
	  comment: 'Id único para cada proveedor'
	)]
	public ?int $id;

	#[OField(
	  comment: 'Nombre del proveedor',
	  nullable: false,
	  max: 50,
	  default: null
	)]
	public ?string $nombre;

	#[OField(
	  comment: 'Foto del proveedor',
	  nullable: true,
	  ref: 'foto.id',
	  default: null
	)]
	public ?int $id_foto;

	#[OField(
	  comment: 'Dirección física del proveedor',
	  nullable: true,
	  max: 200,
	  default: null
	)]
	public ?string $direccion;

	#[OField(
	  comment: 'Teléfono del proveedor',
	  nullable: true,
	  max: 15,
	  default: null
	)]
	public ?string $telefono;

	#[OField(
	  comment: 'Dirección de email del proveedor',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $email;

	#[OField(
	  comment: 'Dirección de la página web del proveedor',
	  nullable: true,
	  max: 100,
	  default: null
	)]
	public ?string $web;

	#[OField(
	  comment: 'Observaciones o notas personales del proveedor',
	  nullable: true,
	  default: null,
	  type: OField::LONGTEXT
	)]
	public ?string $observaciones;

	#[OCreatedAt(
	  comment: 'Fecha de creación del registro'
	)]
	public ?string $created_at;

	#[OUpdatedAt(
	  comment: 'Fecha de última modificación del registro'
	)]
	public ?string $updated_at;

	#[OField(
	  comment: 'Fecha de borrado del proveedor',
	  nullable: true,
	  default: null,
	  type: OField::DATE
	)]
	public ?string $deleted_at;

	private ?array $marcas = null;

	/**
	 * Obtiene el listado de marcas de un proveedor
	 *
	 * @return array Listado de marcas
	 */
	public function getMarcas(): array {
		if (is_null($this->marcas)) {
			$this->loadMarcas();
		}
		return $this->marcas;
	}

	/**
	 * Guarda la lista de marcas
	 *
	 * @param array $m Lista de marcas
	 *
	 * @return void
	 */
	public function setMarcas(array $m): void {
		$this->marcas = $m;
	}

	/**
	 * Carga la lista de marcas de un proveedor
	 *
	 * @return void
	 */
	public function loadMarcas(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `marca` WHERE `id` IN (SELECT `id_marca` FROM `proveedor_marca` WHERE `id_proveedor` = ?) AND `deleted_at` IS NULL";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = Marca::from($res);
		}

		$this->setMarcas($list);
	}

	/**
	 * Obtiene la lista de ids de las marcas del proveedor
	 *
	 * @return array Lista de ids de las marcas
	 */
	public function getMarcasList(): array {
		$list = $this->getMarcas();
		$ret = [];

		foreach ($list as $marca) {
			$ret[] = $marca->id;
		}

		return $ret;
	}

	private ?array $comerciales = null;

	/**
	 * Obtiene el listado de comerciales de un proveedor
	 *
	 * @return array Listado de comerciales
	 */
	public function getComerciales(): array {
		if (is_null($this->comerciales)) {
			$this->loadComerciales();
		}
		return $this->comerciales;
	}

	/**
	 * Guarda la lista de comerciales
	 *
	 * @param array $c Lista de comerciales
	 *
	 * @return void
	 */
	public function setComerciales(array $c): void {
		$this->comerciales = $c;
	}

	/**
	 * Carga la lista de comerciales de un proveedor
	 *
	 * @return void
	 */
	public function loadComerciales(): void {
		$db = new ODB();
		$sql = "SELECT * FROM `comercial` WHERE `id_proveedor` = ? AND `deleted_at` IS NULL";
		$db->query($sql, [$this->id]);
		$list = [];

		while ($res=$db->next()) {
			$list[] = Comercial::from($res);
		}

		$this->setComerciales($list);
	}

	/**
	 * Función para obtener la url de la imagen del logo
	 *
	 * @return string Url de la imagen o null si no tiene
	 */
	public function getFoto(): ?string {
		global $core;
		$ruta_foto = $this->getRutaFoto();
		if (!file_exists($ruta_foto)) {
			return null;
		}
		return $core->config->getUrl('base') . "/proveedores/{$this->id}.webp";
	}

	/**
	 * Obtiene la ruta física a la imagen del logo
	 *
	 * @return string Ruta del archivo de la imagen
	 */
	public function getRutaFoto(): string {
		global $core;
		return $core->config->getDir('public') . "proveedores/{$this->id}.webp";
	}
}
