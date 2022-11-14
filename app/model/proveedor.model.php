<?php declare(strict_types=1);

namespace OsumiFramework\App\Model;

use OsumiFramework\OFW\DB\OModel;
use OsumiFramework\OFW\DB\ODB;

class Proveedor extends OModel {
function __construct() {
		$model = [
			'id' => [
				'type'    => OModel::PK,
				'comment' => 'Id único para cada proveedor'
			],
			'nombre' => [
				'type'    => OModel::TEXT,
				'nullable' => false,
				'default' => null,
				'size' => 50,
				'comment' => 'Nombre del proveedor'
			],
			'id_foto' => [
				'type'    => OModel::NUM,
				'nullable' => true,
				'default' => null,
				'comment' => 'Foto del proveedor',
				'ref' => 'foto.id'
			],
			'direccion' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 200,
				'comment' => 'Dirección física del proveedor'
			],
			'telefono' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 15,
				'comment' => 'Teléfono del proveedor'
			],
			'email' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de email del proveedor'
			],
			'web' => [
				'type'    => OModel::TEXT,
				'nullable' => true,
				'default' => null,
				'size' => 100,
				'comment' => 'Dirección de la página web del proveedor'
			],
			'observaciones' => [
				'type'    => OModel::LONGTEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'Observaciones o notas personales del proveedor'
			],
			'created_at' => [
				'type'    => OModel::CREATED,
				'comment' => 'Fecha de creación del registro'
			],
			'updated_at' => [
				'type'    => OModel::UPDATED,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de última modificación del registro'
			],
			'deleted_at' => [
				'type'    => OModel::DATE,
				'nullable' => true,
				'default' => null,
				'comment' => 'Fecha de borrado del proveedor'
			]
		];

		parent::load($model);
	}

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
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$m = new Marca();
			$m->update($res);
			array_push($list, $m);
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
			array_push($ret, $marca->get('id'));
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
		$db->query($sql, [$this->get('id')]);
		$list = [];

		while ($res=$db->next()) {
			$c = new Comercial();
			$c->update($res);
			array_push($list, $c);
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
		return $core->config->getUrl('base').'/proveedores/'.$this->get('id').'.webp';
	}

	/**
	 * Obtiene la ruta física a la imagen del logo
	 *
	 * @return string Ruta del archivo de la imagen
	 */
	public function getRutaFoto(): string {
		global $core;
		return $core->config->getDir('web').'proveedores/'.$this->get('id').'.webp';
	}
}
