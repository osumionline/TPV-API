<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Empleado;

class empleadosService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Devuelve la lista completa de empleados
	 *
	 * @return array Lista de empleados
	 */
	public function getEmpleados(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `empleado` WHERE `deleted_at` IS NULL ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$empleado = new Empleado();
			$empleado->update($res);
			array_push($list, $empleado);
		}

		return $list;
	}
}