<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\Proveedor;
use OsumiFramework\App\Model\Categoria;
use OsumiFramework\App\Model\ProveedorMarca;
use OsumiFramework\App\Model\Marca;

class articulosService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Devuelve la lista completa de marcas
	 *
	 * @return array Lista de marcas
	 */
	public function getMarcas(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `marca` ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$marca = new Marca();
			$marca->update($res);
			array_push($list, $marca);
		}

		return $list;
	}

	/**
	 * Devuelve la lista completa de proveedores
	 *
	 * @return array Lista de proveedores
	 */
	public function getProveedores(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `proveedor` ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$proveedor = new Proveedor();
			$proveedor->update($res);
			array_push($list, $proveedor);
		}

		return $list;
	}

	/**
	 * Actualiza la lista de marcas de un proveedor
	 *
	 * @param int $id_proveedor Id del proveedor a actualizar
	 *
	 * @param array $marcas Lista nueva de marcas del proveedor
	 *
	 * @return void
	 */
	public function updateProveedoresMarcas(int $id_proveedor, array $marcas): void {
		$db = new ODB();
		$sql = "DELETE FROM `proveedor_marca` WHERE `id_proveedor` = ?";
		$db->query($sql, [$id_proveedor]);

		foreach ($marcas as $id_marca) {
			$pm = new ProveedorMarca();
			$pm->set('id_proveedor', $id_proveedor);
			$pm->set('id_marca',     $id_marca);
			$pm->save();
		}
	}

	/**
	 * Devuelve la lista de subcategorías de una categoría indicada
	 *
	 * @param int $id_parent Id de la categoría de la que obtener las subcategorías
	 *
	 * @return array Lista de subcategorías
	 */
	public function getCategories(int $id_parent): array {
		$db = new ODB();
		$sql = "SELECT * FROM `categoria`";
		if ($id_parent!==-1) {
			$sql .= " WHERE `id_padre` ".( is_null($id_parent) ? "IS NULL" : "= ".$id_parent );
		}

		$ret = [];
		$db->query($sql);

		while ($res=$db->next()) {
			$cat = new Categoria();
			$cat->update($res);

			array_push($ret, $cat);
		}

		return $ret;
	}

	/**
	 * Devuelve la lista completa de categorías "aplanada"
	 *
	 * @param array Lista de opciones para profundizar
	 *
	 * @return array Lista de categorías aplanada
	 */
	public function getCategoryTree(array $options): array {
		$options['id_category'] = array_key_exists('id_category', $options) ? $options['id_category'] : 0;
		$options['depth'] = array_key_exists('depth', $options) ? $options['depth'] : 0;

		$cat = new Categoria();
		$options['depth']++;
		if ($options['id_category']==0) {
			$cat->set('id',       0);
			$cat->set('nombre',   'Inicio');
			$cat->set('id_padre', null);
			$options['depth'] = 0;
		}
		else {
			$cat->find(['id'=>$options['id_category']]);
		}

		$item = [
			'id'          => $cat->get('id'),
			'nombre'      => $cat->get('nombre'),
			'profundidad' => $options['depth'],
			'hijos'    => []
		];

		$children = $this->getCategories($options['id_category']);

		foreach($children as $child) {
			$new_options = [
				'id_category' => $child->get('id'),
				'depth'       => $options['depth']
			];
			array_push($item['hijos'], $this->getCategoryTree($new_options));
		}

		return $item;
	}

	/**
	 * Devuelve un nuevo localizador aleatorio
	 *
	 * @return string Nuevo localizador
	 */
	public function getNewLocalizador(): string {
		$loc = date('y', time()) . str_pad(rand(1, 9999), 4, STR_PAD_LEFT);
		$art = new Articulo();

		if ($art->check(['localizador'=>$loc])) {
			return $this->getNewLocalizador();
		}
		else {
			return $loc;
		}
	}
}