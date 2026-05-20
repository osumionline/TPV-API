<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\App\DTO\InventarioDTO;
use Osumi\OsumiFramework\App\Service\AlmacenService;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\HistoricoAlmacen;

class HistoricoAlmacenTask extends OTask {
	private ?AlmacenService $als = null;

	public function __toString() {
		return "HistoricoAlmacen: tarea para guardar el histórico diario de los datos del almacen";
	}

	public function __construct() {
    $this->als = inject(AlmacenService::class);
  }

	/**
 * Calcula la media del margen de un listado de artículos.
 *
 * Cada artículo debe tener los campos:
 * - pvp
 * - puc
 *
 * Fórmula:
 * margen = (100 * (pvp - puc)) / pvp
 *
 * @param array $array Listado de artículos
 *
 * @return float Media del margen
 */
	function calcularMediaMargen(array $array): float {
		if (count($array) === 0) {
			return 0;
		}

		$suma = 0;

		foreach ($array as $item) {
			$pvp = isset($item['pvp']) ? (float) $item['pvp'] : 0;
			$puc = isset($item['puc']) ? (float) $item['puc'] : 0;

			if ($pvp === 0.0) {
				$margen = 0;
			}
			else {
				$margen = (100 * ($pvp - $puc)) / $pvp;
			}

			$suma += $margen;
		}

		return $suma / count($array);
	}

	public function run(array $options = []): void {
		$data = new InventarioDTO(new ORequest([
			'method' => '',
			'headers' => [],
			'params' => []
		], []));
		$data->descuento = false;
		$data->idCategoria = null;
		$data->idMarca = null;
		$data->idProveedor = null;
		$data->nombre = null;
		$data->num = 50;
		$data->orderBy = null;
		$data->orderSent = null;
		$data->pagina = 1;

		$inventario = $this->als->getInventario($data);

		$total_pvp    = $inventario['total_pvp'];
		$total_puc    = $inventario['total_puc'];
		$media_margen = $this->calcularMediaMargen($inventario['list']);

		echo "TOTAL PVP: ".$total_pvp."\n";
		echo "TOTAL PUC: ".$total_puc."\n";
		echo "MEDIA MARGEN: ".$media_margen."\n";

		$year  = intval(date('Y', time()));
		$month = intval(date('n', time()));
		$day   = intval(date('j', time()));

		$ha = HistoricoAlmacen::findOne(['year' => $year, 'month' => $month, 'day' => $day]);
		if (is_null($ha)) {
			$ha = HistoricoAlmacen::create();
		}

		$ha->year = $year;
		$ha->month = $month;
		$ha->day = $day;
		$ha->total_puc = $total_puc;
		$ha->total_pvp = $total_pvp;
		$ha->media_margen = $media_margen;
		$ha->save();
		echo "Registro ".$ha->id." guardado con fecha ".$day."/".$month."/".$year.".\n";
	}
}
