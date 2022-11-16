<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Venta;

class ventasService extends OService {
	function __construct() {
		$this->loadService();
	}

	/**
	 * Obtiene el listado de ventas de una fecha o un rango dados
	 *
	 * @param string $modo Indica si se deben obtener las ventas de un día ("fecha") o las ventas entre dos fechas ("rango")
	 *
	 * @param string $fecha Indica la fecha de la que obtener las ventas si el modo es "fecha"
	 *
	 * @param string $desde Indica la fecha inicial del rango del que obtener las ventas en el modo "rango"
	 *
	 * @param string $hasta Indica la fecha final del rango del que obtener las ventas en el modo "rango"
	 *
	 * @return array Lista de ventas obtenidas
	 */
	public function getHistoricoVentas(string $modo, ?string $fecha, ?string $desde, ?string $hasta): array {
		$db = new ODB();
		if ($modo == 'fecha') {
			$sql = "SELECT * FROM `venta` WHERE DATE_FORMAT(`created_at`, '%d/%m/%Y') = ? AND `deleted_at` IS NULL ORDER BY `created_at` DESC";
			$db->query($sql, [$fecha]);
		}
		if ($modo == 'rango') {
			$sql = "SELECT * FROM `venta` WHERE `created_at` BETWEEN STR_TO_DATE(?,'%d/%m/%Y') AND STR_TO_DATE(?,'%d/%m/%Y') AND `deleted_at` IS NULL ORDER BY `created_at` DESC";
			$db->query($sql, [$desde, $hasta]);
		}
		$ret = [];

		while ($res = $db->next()) {
			$venta = new Venta();
			$venta->update($res);
			array_push($ret, $venta);
		}

		return $ret;
	}
}
