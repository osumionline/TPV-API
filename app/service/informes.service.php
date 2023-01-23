<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Venta;

class informesService extends OService {
	function __construct() {
		$this->loadService();
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
		if ($number == 0) {
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
			$scheduled_day = $year.'-'.$month.'-'.$day;
			$week_day = date('w',strtotime($scheduled_day));

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
				$v = new Venta();
				$v->update($res);

				$efectivo += $v->getVentaEfectivo();

				if (!is_null($v->get('id_tipo_pago'))) {
					if (!array_key_exists($v->getTipoPago()->get('nombre'), $otros)) {
						$otros[$v->getTipoPago()->get('nombre')] = 0;
					}
					$otros[$v->getTipoPago()->get('nombre')] += $v->getVentaOtros();
				}

				$total_dia += $v->get('total');
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
				array_push($otros_list, [
					'nombre' => $key,
					'valor' => $this->formatNumber($value)
				]);
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
			array_push($ret, $data);
		}

		return $ret;
	}
}
