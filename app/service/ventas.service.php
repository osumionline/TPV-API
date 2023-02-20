<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\DTO\HistoricoDTO;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Model\Reserva;
use OsumiFramework\App\Model\LineaReserva;
use OsumiFramework\App\Utils\AppData;

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
			$sql = "SELECT * FROM `venta` WHERE DATE_FORMAT(`created_at`, '%d/%m/%Y') = ? AND `deleted_at` IS NULL ORDER BY `created_at` ASC";
			$db->query($sql, [$data->getFecha()]);
		}
		if ($data->getModo() == 'rango') {
			$sql = "SELECT * FROM `venta` WHERE `created_at` BETWEEN STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND `deleted_at` IS NULL ORDER BY `created_at` ASC";
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

		require_once $this->getConfig()->getDir('app_utils').'AppData.php';
		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
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
		$venta = new Venta();

		$venta->set('id',             $reserva->get('id'));
		$venta->set('id_cliente',     $reserva->get('id_cliente'));
		$venta->set('total',          $reserva->get('total'));
		$venta->get('pago_mixto',     0);
		$venta->get('entregado',      0);
		$venta->get('entregado_otro', 0);
		$venta->get('id_tipo_pago',   null);
		$venta->get('id_empleado',    null);
		$venta->get('tbai_qr',        null);
		$venta->get('tbai_huella',    null);
		$venta->set('created_at',     $reserva->get('created_at'));

		$lineas = $reserva->getLineas();
		foreach ($lineas as $linea) {
			$linea_venta = new LineaVenta();
			$linea_venta->set('id_venta',        $linea->get('id'));
			$linea_venta->set('id_articulo',     $linea->get('id_articulo'));
			$linea_venta->set('nombre_articulo', $linea->get('nombre_articulo'));
			$linea_venta->set('puc',             $linea->get('puc'));
			$linea_venta->set('pvp',             $linea->get('pvp'));
			$linea_venta->set('iva',             $linea->get('iva'));
			$linea_venta->set('importe',         $linea->get('importe'));
			$linea_venta->set('descuento',       $linea->get('descuento'));
			$linea_venta->set('devuelto',        0);
			$linea_venta->set('unidades',        $linea->get('unidades'));
			$venta->addLinea($linea_venta);
		}

		return $venta;
	}
}
