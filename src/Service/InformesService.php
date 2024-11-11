<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Service\AlmacenService;
use Osumi\OsumiFramework\App\Model\Venta;

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

		foreach ($caducidades as $caducidad) {
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
}
