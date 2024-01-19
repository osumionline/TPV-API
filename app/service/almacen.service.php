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
		$ret = ['list' => [], 'pags' => 0, 'total_pvp' => 0, 'total_puc' => 0];

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
		$proveedores = [];

		while ($res = $db->next()) {
			$articulo = new Articulo();
			$articulo->update($res);

			if (!array_key_exists($articulo->get('id_marca'), $marcas)) {
				$marcas[$articulo->get('id_marca')] = $articulo->getMarca();
			}
			if (!is_null($articulo->get('id_proveedor'))) {
				if (!array_key_exists($articulo->get('id_proveedor'), $proveedores)) {
					$proveedores[$articulo->get('id_proveedor')] = $articulo->getProveedor();
				}
			}

			array_push($ret['list'], [
				'id'                 => $articulo->get('id'),
				'localizador'        => $articulo->get('localizador'),
				'marca'              => $marcas[$articulo->get('id_marca')]->get('nombre'),
				'proveedor'          => !is_null($articulo->get('id_proveedor')) ? $proveedores[$articulo->get('id_proveedor')]->get('nombre') : null,
				'referencia'         => $articulo->get('referencia'),
				'nombre'             => $articulo->get('nombre'),
				'stock'              => $articulo->get('stock'),
				'puc'                => $articulo->get('puc'),
				'pvp'                => $articulo->get('pvp'),
				'has_codigos_barras' => (count($articulo->getNotDefaultCodigosBarras()) > 0),
				'observaciones'      => $articulo->get('observaciones')
			]);
		}

		$sql_count = "SELECT COUNT(*) AS `num`";
		$db->query($sql_count.$sql_body);

		$res = $db->next();
		$ret['pags'] = $res['num'];

		// Calculo total PVP de la selección
		$sql_sum = "SELECT SUM(a.`stock` * a.`pvp`) AS `total_pvp`";
		$db->query($sql_sum.$sql_body);

		$res = $db->next();
		if (!is_null($res['total_pvp'])) {
			$ret['total_pvp'] = round($res['total_pvp'], 2);
		}

		// Calculo total PUC de la selección
		$sql_sum = "SELECT SUM(a.`stock` * a.`puc`) AS `total_puc`";
		$db->query($sql_sum.$sql_body);

		$res = $db->next();
		if (!is_null($res['total_puc'])) {
			$ret['total_puc'] = round($res['total_puc'], 2);
		}

		return $ret;
	}
}
