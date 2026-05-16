<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Service\AlmacenService;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Marca;
use Osumi\OsumiFramework\App\DTO\CaducidadesDTO;
use \DateTime;

class InformesService extends OService {
	private ?AlmacenService $as = null;

	public function __construct() {
		$this->as = inject(AlmacenService::class);
	}

	/**
	 * Función para formatear un número
	 *
	 * @param float $number Número a formatear
	 *
	 * @return string Número formateado
	 */
	private function formatNumber(?float $number): string {
		if (is_null($number)) {
			return 'null';
		}
		if ($number === 0) {
			return '0';
		}
		return number_format($number, 2, '.', '');
	}

	/**
	 * Función para obtener los datos del informe simple
	 *
	 * @param int $month Mes del que obtener los datos
	 *
	 * @param int $year Año del que obtener los datos
	 *
	 * @return array Array con los datos obtenidos
	 */
	public function getInformeSimple(int $month, int $year): array {
		$number = cal_days_in_month(CAL_GREGORIAN,$month, $year);
		$days = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
		$db = new ODB();
		$ret = [];
		$suma = 0;

		for ($day = 1; $day <= $number; $day++) {
			$scheduled_day = $year . '-' . $month . '-' . $day;
			$week_day = date('w', strtotime($scheduled_day));

			// Tickets
			$sql = "SELECT MIN(`num_venta`) AS `min` FROM `venta` WHERE DATE(`created_at`) = '".$scheduled_day."'";
			$db->query($sql);
			$res = $db->next();
			$min_ticket = null;
			if (!is_null($res)) {
				$min_ticket = $res['min'];
			}
			$sql = "SELECT MAX(`num_venta`) AS `max` FROM `venta` WHERE DATE(`created_at`) = '".$scheduled_day."'";
			$db->query($sql);
			$res = $db->next();
			$max_ticket = null;
			if (!is_null($res)) {
				$max_ticket = $res['max'];
			}

			// Ventas
			$efectivo  = 0;
			$otros     = [];
			$total_dia = 0;

			$sql = "SELECT * FROM `venta` WHERE DATE(`created_at`) = '".$scheduled_day."'";
			$db->query($sql);
			while ($res = $db->next()) {
				$v = Venta::from($res);

				$efectivo += $v->getVentaEfectivo();

				if (!is_null($v->id_tipo_pago)) {
					if (!array_key_exists($v->getTipoPago()->nombre, $otros)) {
						$otros[$v->getTipoPago()->nombre] = 0;
					}
					$otros[$v->getTipoPago()->nombre] += $v->getVentaOtros();
				}

				$total_dia += $v->total;
			}
			$suma += $total_dia;

			if ($total_dia === 0) {
				$min_ticket = null;
				$max_ticket = null;
				$efectivo   = null;
				$otros      = [];
				$total_dia  = null;
			}

			$otros_list = [];
			foreach ($otros as $key => $value) {
				$otros_list[] = [
					'nombre' => $key,
					'valor'  => $this->formatNumber($value)
				];
			}

			$data = [
				'num'        => $day,
				'week_day'   => $days[$week_day],
				'min_ticket' => $min_ticket,
				'max_ticket' => $max_ticket,
				'efectivo'   => $this->formatNumber($efectivo),
				'otros'      => $otros_list,
				'total_dia'  => $this->formatNumber($total_dia),
				'suma'       => $this->formatNumber($suma)
			];
			$ret[] = $data;
		}

		return $ret;
	}

	/**
	 * Función para obtener los datos para el informe de caducidades
	 *
	 * @param CaducidadesDTO $data Filtros usados para buscar las caducidades
	 *
	 * @return array Lista con los resultados obtenidos
	 */
	public function getInformeCaducidades(CaducidadesDTO $data): array {
		$caducidades = $this->as->getCaducidades($data);
		$ret = [];

		foreach ($caducidades['list'] as $caducidad) {
			$fecha = new DateTime($caducidad->created_at);
			$year  = (int) $fecha->format('Y');
			$month = (int) $fecha->format('m');

			if (!isset($ret[$year])) {
				$ret[$year] = [
					"year"          => $year,
					"totalUnidades" => 0,
					"totalPVP"      => 0,
					"totalPUC"      => 0,
					"months"        => []
				];
			}

      $current_year = &$ret[$year];

			// Sumar al total del año
			$current_year["totalUnidades"] += $caducidad->unidades;
			$current_year["totalPVP"]      += $caducidad->pvp * $caducidad->unidades;
			$current_year["totalPUC"]      += $caducidad->puc * $caducidad->unidades;

			// Verificamos si ya existe la entrada para el mes en el año actual
			if (!isset($current_year["months"][$month])) {
				$current_year["months"][$month] = [
					"month"         => $month,
					"totalUnidades" => 0,
					"totalPVP"      => 0,
					"totalPUC"      => 0,
					"brands"        => []
				];
			}

			// Referencia al mes actual
			$current_month = &$current_year["months"][$month];

			// Sumar al total del mes
			$current_month["totalUnidades"] += $caducidad->unidades;
			$current_month["totalPVP"]      += $caducidad->pvp * $caducidad->unidades;
			$current_month["totalPUC"]      += $caducidad->puc * $caducidad->unidades;

			$nombre_marca = $caducidad->getArticulo()->getMarca()->nombre;

			// Verificamos si ya existe la entrada para la marca en el mes actual
			if (!isset($current_month["brands"][$nombre_marca])) {
				$current_month["brands"][$nombre_marca] = [
					"name"          => $nombre_marca,
					"totalUnidades" => 0,
					"totalPVP"      => 0,
					"totalPUC"      => 0
				];
			}

			// Referencia a la marca actual
			$current_marca = &$current_month["brands"][$nombre_marca];

			// Sumar al total de la marca
			$current_marca["totalUnidades"] += $caducidad->unidades;
			$current_marca["totalPVP"]      += $caducidad->pvp * $caducidad->unidades;
			$current_marca["totalPUC"]      += $caducidad->puc * $caducidad->unidades;
		}

		foreach ($ret as &$year_data) {
			$year_data["months"] = array_values($year_data["months"]);
			foreach ($year_data["months"] as &$month_Data) {
				$month_Data["brands"] = array_values($month_Data["brands"]);
			}
		}

		return array_values($ret);
	}

	/**
	 * Función para obtener los datos del informe detallado
	 *
	 * @param int $month Mes del que obtener los datos
	 * @param int $year  Año del que obtener los datos
	 *
	 * @return array Array con los datos obtenidos
	 */
	public function getInformeDetallado(int $month, int $year): array {
		$previous_month = $month - 1;
		$previous_year = $year;
		if ($previous_month === 0) {
			$previous_month = 12;
			$previous_year--;
		}

		$db = new ODB();
		$ret = ['marcas' => [], 'articulos' => [], 'ventas' => []];

		// =======================
		// CONSULTA DE MARCAS
		// =======================

		$marcas_sql = "
			SELECT m.`nombre` AS `marca`,
				ROUND(COALESCE(SUM(lv.`pvp` * lv.`unidades`), 0), 2) AS `total_ventas_pvp`,
				ROUND(COALESCE(SUM((lv.`pvp` - lv.`puc`) * lv.`unidades`), 0), 2) AS `total_beneficio`
			FROM `marca` m
			LEFT JOIN `articulo` a ON a.`id_marca` = m.`id`
			LEFT JOIN `linea_venta` lv ON lv.`id_articulo` = a.`id`
				AND YEAR(lv.`created_at`) = %d AND MONTH(lv.`created_at`) = %d
			GROUP BY m.`nombre`
			ORDER BY `total_ventas_pvp` DESC
		";

		// Consulta mes anterior
		$marcas_prev = [];
		$db->query(sprintf($marcas_sql, $previous_year, $previous_month));
		while ($res = $db->next()) {
			$marca = $res['marca'];
			$pvp = $res['total_ventas_pvp'];
			$benef = $res['total_beneficio'];
			$marcas_prev[$marca] = ($pvp > 0) ? round(($benef / $pvp) * 100, 2) : 0;
		}

		// Consulta mes actual
		$db->query(sprintf($marcas_sql, $year, $month));
		while ($res = $db->next()) {
			$pvp = $res['total_ventas_pvp'];
			$benef = $res['total_beneficio'];

			$margen = ($pvp > 0) ? round(($benef / $pvp) * 100, 2) : 0;
			$margen_prev = $marcas_prev[$res['marca']] ?? null;
			$diferencia = (!is_null($margen_prev)) ? round($margen - $margen_prev, 2) : null;

			$ret['marcas'][] = [
				'marca' => $res['marca'],
				'total_ventas_pvp' => $pvp,
				'total_beneficio' => $benef,
				'margen' => $margen,
				'margen_anterior' => $margen_prev,
				'margen_diferencia' => $diferencia
			];
		}

		$total_ventas_marcas = array_sum(array_column($ret['marcas'], 'total_ventas_pvp'));

		foreach ($ret['marcas'] as &$marca) {
			$marca['porcentaje_sobre_total'] = ($total_ventas_marcas > 0)
				? round(($marca['total_ventas_pvp'] / $total_ventas_marcas) * 100, 2)
				: 0;
		}

		// =======================
		// CONSULTA DE ARTÍCULOS
		// =======================

		$articulos_sql = "
			SELECT
				a.`id` AS `id_articulo`,
				a.`nombre` AS `nombre_articulo`,
				m.`nombre` AS `nombre_marca`,
				SUM(lv.`unidades`) AS `total_unidades_vendidas`,
				ROUND(SUM(lv.`pvp` * lv.`unidades`), 2) AS `total_ventas_pvp`,
				ROUND(SUM((lv.`pvp` - lv.`puc`) * lv.`unidades`), 2) AS `total_beneficio`
			FROM `linea_venta` lv
			JOIN `articulo` a ON lv.`id_articulo` = a.`id`
			LEFT JOIN `marca` m ON a.`id_marca` = m.`id`
			WHERE YEAR(lv.`created_at`) = %d AND MONTH(lv.`created_at`) = %d
			GROUP BY a.`id`, a.`nombre`, m.`nombre`
			ORDER BY `total_ventas_pvp` DESC
			LIMIT 50
		";

		// Total ventas del mes
		$sql_total_ventas = "
			SELECT COUNT(DISTINCT id) AS total_ventas
			FROM venta
			WHERE YEAR(created_at) = %d AND MONTH(created_at) = %d
		";
		$db->query(sprintf($sql_total_ventas, $year, $month));
		$res = $db->next();
		$total_ventas_mes = (int) $res['total_ventas'];
		$ret['ventas']['total'] = $total_ventas_mes;

		// Total ventas del mes anterior
		$db->query(sprintf($sql_total_ventas, $previous_year, $previous_month));
		$res = $db->next();
		$total_ventas_mes_anterior = (int) $res['total_ventas'];
		$ret['ventas']['total_anterior'] = $total_ventas_mes_anterior;
		$ret['ventas']['total_diferencia'] = $total_ventas_mes - $total_ventas_mes_anterior;

		// Beneficio medio actual
		$sql_beneficio_medio = "
			SELECT lv.id_venta, SUM((lv.pvp - lv.puc) * lv.unidades) AS beneficio
			FROM linea_venta lv
			WHERE YEAR(lv.created_at) = %d AND MONTH(lv.created_at) = %d
			GROUP BY lv.id_venta
		";
		$db->query(sprintf($sql_beneficio_medio, $year, $month));
		$total_beneficio = 0;
		$count = 0;
		while ($res = $db->next()) {
			$total_beneficio += $res['beneficio'];
			$count++;
		}
		$ret['ventas']['beneficio_medio'] = ($count > 0) ? round($total_beneficio / $count, 2) : 0;

		// Beneficio medio anterior
		$db->query(sprintf($sql_beneficio_medio, $previous_year, $previous_month));
		$total_beneficio_anterior = 0;
		$count_anterior = 0;
		while ($res = $db->next()) {
			$total_beneficio_anterior += $res['beneficio'];
			$count_anterior++;
		}
		$beneficio_medio_anterior = ($count_anterior > 0) ? round($total_beneficio_anterior / $count_anterior, 2) : 0;
		$ret['ventas']['beneficio_medio_anterior'] = $beneficio_medio_anterior;
		$ret['ventas']['beneficio_medio_diferencia'] = round($ret['ventas']['beneficio_medio'] - $beneficio_medio_anterior, 2);

		// En cuántas ventas aparece cada artículo
		$ventas_por_articulo = [];
		$sql_ventas_articulos = "
			SELECT lv.id_articulo, COUNT(DISTINCT lv.id_venta) AS num_ventas
			FROM linea_venta lv
			WHERE YEAR(lv.created_at) = %d AND MONTH(lv.created_at) = %d
			GROUP BY lv.id_articulo
		";
		$db->query(sprintf($sql_ventas_articulos, $year, $month));
		while ($res = $db->next()) {
			$ventas_por_articulo[(int)$res['id_articulo']] = (int)$res['num_ventas'];
		}

		// Margen del mes anterior por artículo
		$articulos_prev = [];
		$db->query(sprintf($articulos_sql, $previous_year, $previous_month));
		while ($res = $db->next()) {
			$pvp = $res['total_ventas_pvp'];
			$benef = $res['total_beneficio'];
			$articulos_prev[(int)$res['id_articulo']] = ($pvp > 0) ? round(($benef / $pvp) * 100, 2) : 0;
		}

		// Artículos del mes actual
		$db->query(sprintf($articulos_sql, $year, $month));
		while ($res = $db->next()) {
			$id_articulo = (int) $res['id_articulo'];
			$pvp = (float) $res['total_ventas_pvp'];
			$benef = (float) $res['total_beneficio'];
			$margen = ($pvp > 0) ? round(($benef / $pvp) * 100, 2) : 0;
			$margen_prev = $articulos_prev[$id_articulo] ?? null;
			$margen_diferencia = !is_null($margen_prev) ? round($margen - $margen_prev, 2) : null;

			$num_ventas_articulo = $ventas_por_articulo[$id_articulo] ?? 0;
			$porcentaje_en_ventas = ($total_ventas_mes > 0)
				? round(($num_ventas_articulo / $total_ventas_mes) * 100, 2)
				: 0;

			$ret['articulos'][] = [
				'id_articulo' => $id_articulo,
				'nombre' => $res['nombre_articulo'],
				'marca' => $res['nombre_marca'],
				'total_unidades_vendidas' => (int) $res['total_unidades_vendidas'],
				'total_ventas_pvp' => $pvp,
				'total_beneficio' => $benef,
				'margen' => $margen,
				'margen_anterior' => $margen_prev,
				'margen_diferencia' => $margen_diferencia,
				'porcentaje_en_ventas' => $porcentaje_en_ventas
			];
		}

		return $ret;
	}

	/**
	 * Función para obtener los datos del informe de ventas.
	 *
	 * @param int $id_category Id de la categoría de la que obtener los artículos
	 * @param int $month       Mes del que obtener los datos
	 * @param int $year        Año del que obtener los datos
	 *
	 * @return array Array con los datos obtenidos
	 */
	public function getInformeVentas(int $id_category, int $month, int $year): array {
		$db = new ODB();

		$categories_by_id = [];
		$categories_by_parent = [];

		$sql = "SELECT `id`, `id_padre`, `nombre` FROM `categoria` ORDER BY `nombre`";
		$db->query($sql);

		while ($res = $db->next()) {
			$id = (int) $res['id'];
			$id_parent = is_null($res['id_padre']) ? null : (int) $res['id_padre'];

			$category = [
				'id'       => $id,
				'id_padre' => $id_parent,
				'nombre'   => $res['nombre']
			];

			$categories_by_id[$id] = $category;

			if (!is_null($id_parent)) {
				if (!isset($categories_by_parent[$id_parent])) {
					$categories_by_parent[$id_parent] = [];
				}

				$categories_by_parent[$id_parent][] = $category;
			}
		}

		if (!isset($categories_by_id[$id_category])) {
			return [];
		}

		$category_ids = [];

		$collect_category_ids = function(int $category_id) use (&$collect_category_ids, &$category_ids, $categories_by_parent): void {
			$category_ids[] = $category_id;

			if (!isset($categories_by_parent[$category_id])) {
				return;
			}

			foreach ($categories_by_parent[$category_id] as $subcategory) {
				$collect_category_ids((int) $subcategory['id']);
			}
		};

		$collect_category_ids($id_category);

		$category_ids = array_values(array_unique($category_ids));

		if (count($category_ids) === 0) {
			return [];
		}

		$category_ids_sql = implode(',', $category_ids);

		$date_start = sprintf('%04d-%02d-01 00:00:00', $year, $month);

		$date_end_object = new DateTime($date_start);
		$date_end_object->modify('first day of next month');

		$date_end = $date_end_object->format('Y-m-d 00:00:00');

		$articles_by_category = [];

		$sql = sprintf(
			"SELECT
			a.`id` AS `id`,
			a.`nombre` AS `nombre`,
			a.`id_categoria` AS `id_categoria`,
			a.`id_marca` AS `id_marca`,
			m.`nombre` AS `marca`,
			ROUND(SUM(lv.`importe`), 2) AS `importe`,
			SUM(lv.`unidades`) AS `unidades`,
			CASE
			WHEN SUM(lv.`pvp` * lv.`unidades`) = 0 THEN 0
			ELSE ROUND(
				(
					(
						SUM(lv.`pvp` * lv.`unidades`) -
						SUM(lv.`puc` * lv.`unidades`)
					) / SUM(lv.`pvp` * lv.`unidades`)
				) * 100,
				2
			)
			END AS `margen`
			FROM `articulo` a
			INNER JOIN `marca` m ON m.`id` = a.`id_marca`
			INNER JOIN `linea_venta` lv ON lv.`id_articulo` = a.`id`
			WHERE a.`id_categoria` IN (%s)
			AND lv.`created_at` >= '%s'
			AND lv.`created_at` < '%s'
			GROUP BY
			a.`id`,
			a.`nombre`,
			a.`id_categoria`,
			a.`id_marca`,
			m.`nombre`
			ORDER BY SUM(lv.`importe`) DESC, a.`nombre`",
			$category_ids_sql,
			$date_start,
			$date_end
		);
		$db->query($sql);

		while ($res = $db->next()) {
			$id_category_article = (int) $res['id_categoria'];

			if (!isset($articles_by_category[$id_category_article])) {
				$articles_by_category[$id_category_article] = [];
			}

			$articles_by_category[$id_category_article][] = [
				'id'       => (int) $res['id'],
				'nombre'   => $res['nombre'],
				'idMarca'  => (int) $res['id_marca'],
				'marca'    => $res['marca'],
				'importe'  => (float) $res['importe'],
				'unidades' => (int) $res['unidades'],
				'margen'   => (float) $res['margen']
			];
		}

		$build_category_tree = function(int $category_id) use (&$build_category_tree, $categories_by_id, $categories_by_parent, $articles_by_category): ?array {
			$category = $categories_by_id[$category_id];

			$ret = [
				'id'            => (int) $category['id'],
				'nombre'        => $category['nombre'],
				'articulos'     => $articles_by_category[$category_id] ?? [],
				'total'         => 0,
				'unidades'      => 0,
				'margen'        => 0,
				'subcategorias' => []
			];

			$total = 0;
			$unidades = 0;
			$margen_sum = 0;
			$margen_count = 0;

			foreach ($ret['articulos'] as $article) {
				$total += (float) $article['importe'];
				$unidades += (int) $article['unidades'];
				$margen_sum += (float) $article['margen'];
				$margen_count++;
			}

			if (isset($categories_by_parent[$category_id])) {
				foreach ($categories_by_parent[$category_id] as $subcategory) {
					$subcategory_data = $build_category_tree((int) $subcategory['id']);

					if (!is_null($subcategory_data)) {
						$total += (float) $subcategory_data['total'];
						$unidades += (int) $subcategory_data['unidades'];
						$margen_sum += (float) $subcategory_data['_margen_sum'];
						$margen_count += (int) $subcategory_data['_margen_count'];

						unset($subcategory_data['_margen_sum']);
						unset($subcategory_data['_margen_count']);

						$ret['subcategorias'][] = $subcategory_data;
					}
				}
			}

			$ret['total'] = round($total, 2);
			$ret['unidades'] = $unidades;
			$ret['margen'] = $margen_count > 0 ? round($margen_sum / $margen_count, 2) : 0;

			if ($ret['total'] === 0.0) {
				return null;
			}

			$ret['_margen_sum'] = $margen_sum;
			$ret['_margen_count'] = $margen_count;

			return $ret;
		};

		$ret = $build_category_tree($id_category);

		if (is_null($ret)) {
			return [];
		}

		unset($ret['_margen_sum']);
		unset($ret['_margen_count']);

		return $ret;
	}
}
