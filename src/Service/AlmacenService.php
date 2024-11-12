<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\DTO\InventarioDTO;
use Osumi\OsumiFramework\App\DTO\CaducidadesDTO;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\Caducidad;

class AlmacenService extends OService {
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

		if (!is_null($data->nombre)) {
			$parts = explode(' ', $data->nombre);
			for ($i = 0; $i < count($parts); $i++) {
				$parts[$i] = OTools::slugify($parts[$i]);
			}
			$sql_body .= " AND a.`slug` LIKE '%" . implode('%', $parts) . "%'";
		}
		if (!is_null($data->id_marca)) {
			$sql_body .= " AND a.`id_marca` = " . $data->id_marca;
		}
		if (!is_null($data->id_proveedor)) {
			$sql_body .= " AND a.`id_proveedor` = " . $data->id_proveedor;
		}
		if ($data->descuento) {
			$sql_body .= " AND a.`pvp_descuento` IS NOT NULL";
		}

		$sql_limit = "";
		if (!is_null($data->order_by) && !is_null($data->order_sent)) {
			if ($data->order_by !== 'marca') {
				$sql_limit = " ORDER BY a.`" . $data->order_by . "` " . strtoupper($data->order_sent);
			}
			else {
				$sql_limit = " ORDER BY m.`nombre` " . strtoupper($data->order_sent);
			}
		}
		else {
			$sql_limit = " ORDER BY m.`nombre` ASC, a.`nombre` ASC";
		}
		if (!is_null($data->num)) {
			$lim = ($data->pagina - 1) * $data->num;
			$sql_limit .= " LIMIT " . $lim . "," . $data->num;
		}

		$db->query($sql . $sql_body . $sql_limit);
		$marcas = [];
		$proveedores = [];

		while ($res = $db->next()) {
			$articulo = Articulo::from($res);

			if (!array_key_exists($articulo->id_marca, $marcas)) {
				$marcas[$articulo->id_marca] = $articulo->getMarca();
			}
			if (!is_null($articulo->id_proveedor)) {
				if (!array_key_exists($articulo->id_proveedor, $proveedores)) {
					$proveedores[$articulo->id_proveedor] = $articulo->getProveedor();
				}
			}

			$ret['list'][] = [
				'id'                 => $articulo->id,
				'localizador'        => $articulo->localizador,
				'marca'              => $marcas[$articulo->id_marca]->nombre,
				'proveedor'          => !is_null($articulo->id_proveedor) ? $proveedores[$articulo->id_proveedor]->nombre : null,
				'referencia'         => $articulo->referencia,
				'nombre'             => $articulo->nombre,
				'stock'              => $articulo->stock,
				'puc'                => $articulo->puc,
				'pvp'                => $articulo->pvp,
				'has_codigos_barras' => (count($articulo->getNotDefaultCodigosBarras()) > 0),
				'observaciones'      => $articulo->observaciones
			];
		}

		$sql_count = "SELECT COUNT(*) AS `num`";
		$db->query($sql_count . $sql_body);

		$res = $db->next();
		$ret['pags'] = (int) $res['num'];

		// Calculo total PVP de la selección
		$sql_sum = "SELECT SUM(a.`stock` * a.`pvp`) AS `total_pvp`";
		$db->query($sql_sum.$sql_body);

		$res = $db->next();
		if (!is_null($res['total_pvp'])) {
			$ret['total_pvp'] = round($res['total_pvp'], 2);
		}

		// Calculo total PUC de la selección
		$sql_sum = "SELECT SUM(a.`stock` * a.`puc`) AS `total_puc`";
		$db->query($sql_sum . $sql_body);

		$res = $db->next();
		if (!is_null($res['total_puc'])) {
			$ret['total_puc'] = round($res['total_puc'], 2);
		}

		return $ret;
	}

	/**
	 * Función para obtener el listado de caducidades
	 *
	 * @param CaducidadesDTO $data Filtros usados para buscar las caducidades
	 *
	 * @return array Lista con los resultados obtenidos
	 */
	public function getCaducidades(CaducidadesDTO $data): array {
		$db = new ODB();
		$ret = [
			'list'           => [],
			'pags'           => 0,
			'total_unidades' => 0,
			'total_pvp'      => 0,
			'total_puc'      => 0
		];
		$sql = "SELECT c.*";
		$sql_body = " FROM `caducidad` c, `articulo` a, `marca` m WHERE c.`id_articulo` = a.`id` AND m.`id` = a.`id_marca`";

		$conditions = [];
		if (!is_null($data->year)) {
			$conditions[] = "YEAR(c.`created_at`) = " . $data->year;
		}
		if (!is_null($data->month)) {
			$conditions[] = "MONTH(c.`created_at`) = " . $data->month;
		}
		if (!is_null($data->id_marca)) {
			$conditions[] = "a.`id_marca` = ".$data->id_marca;
		}
		if (!is_null($data->nombre)) {
			$parts = explode(' ', $data->nombre);
			for ($i = 0; $i < count($parts); $i++) {
				$parts[$i] = OTools::slugify($parts[$i]);
			}
			$conditions[] = "a.`slug` LIKE '%" . implode('%', $parts) . "%'";
		}
		if (count($conditions) > 0) {
			$sql_body .= " AND ";
		}
		$sql_body .= implode(" AND ", $conditions);

		$sql_limit = "";
		if (!is_null($data->order_by) && !is_null($data->order_sent)) {
			if ($data->order_by !== 'marca') {
				$sql_limit = " ORDER BY a.`" . $data->order_by . "` " . strtoupper($data->order_sent);
			}
			else {
				$sql_limit = " ORDER BY m.`nombre` " . strtoupper($data->order_sent);
			}
		}
		else {
			$sql_limit = " ORDER BY m.`nombre` ASC, a.`nombre` ASC";
		}
		if (!is_null($data->pagina) && !is_null($data->num)) {
			$lim = ($data->pagina - 1) * $data->num;
			$sql_limit = " LIMIT " . $lim . "," . $data->num;
		}

		$db->query($sql . $sql_body . $sql_limit);

		while ($res = $db->next()) {
			$caducidad = Caducidad::from($res);
			$ret['list'][] = $caducidad;
			$ret['total_unidades'] += $caducidad->unidades;
			$ret['total_pvp']      += $caducidad->unidades * $caducidad->pvp;
			$ret['total_puc']      += $caducidad->unidades * $caducidad->puc;
		}

		$sql_count = "SELECT COUNT(*) AS `num`";
		$db->query($sql_count . $sql_body);
		$res = $db->next();
		$ret['pags'] = (int) $res['num'];

		return $ret;
	}
}
