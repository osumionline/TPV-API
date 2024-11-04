<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Model\Categoria;

class CategoriasService extends OService {
  /**
	 * Devuelve la lista de subcategorías de una categoría indicada
	 *
	 * @param int $id_parent Id de la categoría de la que obtener las subcategorías
	 *
	 * @return array Lista de subcategorías
	 */
	public function getCategories(?int $id_parent): array {
		$db = new ODB();
		$sql = "SELECT * FROM `categoria`";
		if ($id_parent !== -1) {
			$sql .= " WHERE `id_padre` ".( is_null($id_parent) ? "IS NULL" : "= ".$id_parent );
		}

		$ret = [];
		$db->query($sql);

		while ($res = $db->next()) {
			$ret[] = Categoria::from($res);
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
		$options['id_category'] = array_key_exists('id_category', $options) ? $options['id_category'] : null;
		$options['depth']       = array_key_exists('depth', $options)       ? $options['depth']       : 0;

		$cat = Categoria::create();
		$options['depth']++;
		if (is_null($options['id_category'])) {
			$cat->id       = 0;
			$cat->nombre   = 'Inicio';
			$cat->id_padre = null;
			$options['depth'] = 0;
		}
		else {
			$cat = Category::findOne(['id' => $options['id_category']]);
		}

		$item = [
			'id'          => $cat->id,
			'nombre'      => $cat->nombre,
			'profundidad' => $options['depth'],
			'hijos'       => []
		];

		$children = $this->getCategories($options['id_category']);

		foreach($children as $child) {
			$new_options = [
				'id_category' => $child->id,
				'depth'       => $options['depth']
			];
			$item['hijos'][] = $this->getCategoryTree($new_options);
		}

		return $item;
	}
}
