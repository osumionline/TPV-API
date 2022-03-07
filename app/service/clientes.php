<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Cliente;

class clientesService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

  /**
	 * Busca entre los clientes
	 *
	 * @return array Lista de marcas
	 */
	public function searchClientes(string $name): array {
		$db = new ODB();
		$sql = "SELECT * FROM `cliente` WHERE LOWER(CONCAT(`nombre_apellidos`, ' ', COALESCE(`telefono`, ''), ' ', COALESCE(`email`, ''))) LIKE '%".strtolower($name)."%' AND `deleted_at` IS NULL ORDER BY `nombre_apellidos`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$cliente = new Cliente();
			$cliente->update($res);
			array_push($list, $cliente);
		}

		return $list;
	}
}
