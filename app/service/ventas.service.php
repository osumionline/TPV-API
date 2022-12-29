<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\DTO\HistoricoDTO;
use OsumiFramework\App\Model\Venta;

class ventasService extends OService {
	function __construct() {
		$this->loadService();
	}

	/**
	 * Obtiene el listado de ventas de una fecha o un rango dados
	 *
	 * @param HistoricoDTO $data Filtros usados para buscar ventas
	 *
	 * @return array Lista de ventas obtenidas
	 */
	public function getHistoricoVentas(HistoricoDTO $data): array {
		$db = new ODB();
		if ($data->getModo() == 'fecha') {
			$sql = "SELECT * FROM `venta` WHERE DATE_FORMAT(`created_at`, '%d/%m/%Y') = ? AND `deleted_at` IS NULL ORDER BY `created_at` DESC";
			$db->query($sql, [$data->getFecha()]);
		}
		if ($data->getModo() == 'rango') {
			$sql = "SELECT * FROM `venta` WHERE `created_at` BETWEEN STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND `deleted_at` IS NULL ORDER BY `created_at` DESC";
			$db->query($sql, [$data->getDesde().' 00:00:00', $data->getHasta().' 23:59:59']);
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
