<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Marca;

class marcasService extends OService {
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
		$sql = "SELECT * FROM `marca` WHERE `deleted_at` IS NULL ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$marca = new Marca();
			$marca->update($res);
			array_push($list, $marca);
		}

		return $list;
	}
}
