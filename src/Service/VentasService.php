<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\DTO\HistoricoDTO;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\LineaVenta;
use Osumi\OsumiFramework\App\Model\Reserva;
use Osumi\OsumiFramework\App\Utils\AppData;

class VentasService extends OService {
	/**
	 * Obtiene el listado de ventas de una fecha o un rango dados
	 *
	 * @param HistoricoDTO $data Filtros usados para buscar ventas
	 *
	 * @return array Lista de ventas obtenidas
	 */
	public function getHistoricoVentas(HistoricoDTO $data): array {
		$db = new ODB();
		if ($data->modo === 'fecha') {
			$sql = "SELECT * FROM `venta` WHERE DATE_FORMAT(`created_at`, '%d/%m/%Y') = ? AND `deleted_at` IS NULL ORDER BY `created_at` ASC";
			$db->query($sql, [$data->fecha]);
		}
		if ($data->modo === 'rango') {
			$sql = "SELECT * FROM `venta` WHERE `created_at` BETWEEN STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND `deleted_at` IS NULL ORDER BY `created_at` ASC";
			$db->query($sql, [$data->desde.' 00:00:00', $data->hasta.' 23:59:59']);
		}
		$ret = [];

		while ($res = $db->next()) {
			$ret[] = Venta::from($res);
		}

		return $ret;
	}

	/**
	 * Función para obtener un nuevo número de venta
	 *
	 * @return int Nuevo número de venta generado
	 */
	public function generateNumVenta(): int {
		$db = new ODB();
		$sql = "SELECT MAX(`num_venta`) AS `num` FROM `venta`";
		$db->query($sql);
		$res = $db->next();

		if (!is_null($res['num'])) {
			return intval($res['num']) + 1;
		}

		// Cargo archivo de configuración
		OTools::checkOfw('cache');
		$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}

		return $app_data->getTicketInicial();
	}

	/**
	 * Función para obtener un objeto Venta a partir de un objeto Reserva
	 *
	 * @param Reserva $reserva Objeto Reserva
	 *
	 * @return Venta Objeto Venta generado
	 */
	public function getVentaFromReserva(Reserva $reserva): Venta {
		$venta = Venta::create();

		$venta->id             = $reserva->id;
		$venta->id_cliente     = $reserva->id_cliente;
		$venta->total          = $reserva->total;
		$venta->pago_mixto     = false;
		$venta->entregado      = 0;
		$venta->entregado_otro = 0;
		$venta->id_tipo_pago   = null;
		$venta->id_empleado    = null;
		$venta->tbai_qr        = null;
		$venta->tbai_huella    = null;
		$venta->created_at     = $reserva->created_at;

		$lineas = $reserva->getLineas();
		foreach ($lineas as $linea) {
			$linea_venta = LineaVenta::create();
			$linea_venta->id_venta        = $linea->id;
			$linea_venta->id_articulo     = $linea->id_articulo;
			$linea_venta->nombre_articulo = $linea->nombre_articulo;
			$linea_venta->puc             = $linea->puc;
			$linea_venta->pvp             = $linea->pvp;
			$linea_venta->iva             = $linea->iva;
			$linea_venta->importe         = $linea->importe;
			$linea_venta->descuento       = $linea->descuento;
			$linea_venta->devuelto        = 0;
			$linea_venta->unidades        = $linea->unidades;
			$venta->addLinea($linea_venta);
		}

		return $venta;
	}
}
