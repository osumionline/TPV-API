<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\DTO\InventarioDTO;
use OsumiFramework\App\Model\Articulo;

class almacenService extends OService {
	function __construct() {
		$this->loadService();
	}

	/**
	 * Función para obtener los resultados del buscador de inventario
	 *
	 * @param InventarioDTO $data Objeto con la información de los filtros para buscar en el inventario
	 *
	 * @return array Lista con los resultados obtenidos
	 */
	public function getInventario(InventarioDTO $data): array {
		$db = new ODB();
		$sql = "SELECT a.*";
		$sql_body = " FROM `articulo` a, `marca` m WHERE a.`id_marca` = m.`id` AND a.`deleted_at` IS NULL";
		$ret = ['list' => [], 'pags' => 0];

		if (!is_null($data->getNombre())) {
			$parts = explode(' ', $data->getNombre());
			for ($i = 0; $i<count($parts); $i++) {
				$parts[$i] = OTools::slugify($parts[$i]);
			}
			$sql_body .= " AND a.`slug` LIKE '%".implode('%', $parts)."%'";
		}
		if (!is_null($data->getIdMarca())) {
			$sql_body .= " AND a.`id_marca` = ".$data->getIdMarca();
		}
		if (!is_null($data->getIdProveedor())) {
			$sql_body .= " AND a.`id_proveedor` = ".$data->getIdProveedor();
		}

		$sql_limit = "";
		if (!is_null($data->getOrderBy()) && !is_null($data->getOrderSent())) {
			if ($data->getOrderBy() != 'marca') {
				$sql_limit = " ORDER BY a.`".$data->getOrderBy()."` ".strtoupper($data->getOrderSent());
			}
			else {
				$sql_limit = " ORDER BY m.`nombre` ".strtoupper($data->getOrderSent());
			}
		}
		else {
			$sql_limit = " ORDER BY m.`nombre` ASC, a.`nombre` ASC";
		}
		if (!is_null($data->getNum())) {
			$lim = ($data->getPagina() - 1) * $data->getNum();
			$sql_limit .= " LIMIT ".$lim.",".$data->getNum();
		}

		$db->query($sql.$sql_body.$sql_limit);
		$marcas = [];

		while ($res = $db->next()) {
			$articulo = new Articulo();
			$articulo->update($res);

			if (!array_key_exists($articulo->get('id_marca'), $marcas)) {
				$marcas[$articulo->get('id_marca')] = $articulo->getMarca();
			}

			array_push($ret['list'], [
				'id'          => $articulo->get('id'),
			  'localizador' => $articulo->get('localizador'),
			  'marca'       => $marcas[$articulo->get('id_marca')]->get('nombre'),
			  'referencia'  => $articulo->get('referencia'),
			  'nombre'      => $articulo->get('nombre'),
			  'stock'       => $articulo->get('stock'),
				'puc'         => $articulo->get('puc'),
			  'pvp'         => $articulo->get('pvp')
			]);
		}

		$sql_count = "SELECT COUNT(*) AS `num`";
		$db->query($sql_count.$sql_body);

		$res = $db->next();
		$ret['pags'] = $res['num'];

		return $ret;
	}
}
