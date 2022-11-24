<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\DTO\PedidosFilterDTO;
use OsumiFramework\App\Model\Pedido;

class comprasService extends OService {
	function __construct() {
		$this->loadService();
	}

	/**
	 * Función para obtener el listado de pedidos
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 *
	 * @return array Resultado de la búsqueda de pedidos
	 */
	public function getPedidos(PedidosFilterDTO $data): array {
		$ret = [
			'guardados' => [],
			'guardados_pags' => 0,
			'recepcionados' => [],
			'recepcionados_pags' => 0
		];

		$guardados = $this->getPedidosGuardados($data);
		$ret['guardados']      = $guardados['list'];
		$ret['guardados_pags'] = $guardados['pags'];

		$recepcionados = $this->getPedidosRecepcionados($data);
		$ret['recepcionados']      = $recepcionados['list'];
		$ret['recepcionados_pags'] = $recepcionados['pags'];

		return $ret;
	}

	/**
	 * Función para obtener el listado de pedidos guardados o recepcionados en base a los filtros introducidos
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 *
	 * @param bool $recepcionado Indica si buscar en los pedidos recepcionados o los guardados
	 *
	 * @return array Listado de pedidos y el número de páginas que hay basándose en los filtros indicados
	 */
	public function getPedidosList(PedidosFilterDTO $data, bool $recepcionado): array {
		$db = new ODB();
		$c  = $this->getConfig();

		$ret = ['list' => [], 'pags' => 0];

		$sql_count = "SELECT COUNT(*) AS `num` FROM `pedido` WHERE `recepcionado` = ".($recepcionado ? '1' : '0');
		$sql_all = "SELECT * FROM `pedido` WHERE `recepcionado` = ".($recepcionado ? '1' : '0');
		$sql = "";

		if (!is_null($data->getFechaDesde())) {
			$sql .= " AND `created_at` > STR_TO_DATE('".$data->getFechaDesde()." 00:00:00', '%Y-%m-%d %H:%i:%s')";
		}
		if (!is_null($data->getFechaHasta())) {
			$sql .= " AND `created_at` < STR_TO_DATE('".$data->getFechaHasta()." 23:59:59', '%Y-%m-%d %H:%i:%s')";
		}
		if (!is_null($data->getIdProveedor())) {
			$sql .= " AND `id_proveedor` = ".$data->getIdProveedor();
		}
		if (!is_null($data->getAlbaran())) {
			$sql .= " AND `albaran` LIKE '%".$data->getAlbaran()."%'";
		}
		if (!is_null($data->getImporteDesde())) {
			$sql .= " AND `importe` >= ".$data->getImporteDesde();
		}
		if (!is_null($data->getImporteHasta())) {
			$sql .= " AND `importe` <= ".$data->getImporteHasta();
		}
		$sql .= " ORDER BY `created_at` DESC";

		$db->query($sql_count.$sql);
		$res = $db->next();
		$ret['pags'] = ceil($res['NUM'] / $c->getExtra('num_por_pag'));

		$lim = ($data->getPagina() - 1) * $c->getExtra('num_por_pag');
		$sql_pag = $sql_all.$sql;
		$sql_pag .= " LIMIT ".$lim.",".$c->getExtra('num_por_pag');

		$db->query($sql_pag);
		while ($res = $db->next()) {
			$p = new Pedido();
			$p->update($res);
			array_push($ret['list'], $p);
		}

		return $ret;
	}

	/**
	 * Función para obtener el listado de pedidos guardados en base a los filtros introducidos
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 *
	 * @return array Listado de pedidos y el número de páginas que hay basándose en los filtros indicados
	 */
	public function getPedidosGuardados(PedidosFilterDTO $data): array {
		return $this->getPedidosList($data, false);
	}

	/**
	 * Función para obtener el listado de pedidos recepcionados en base a los filtros introducidos
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 *
	 * @return array Listado de pedidos y el número de páginas que hay basándose en los filtros indicados
	 */
	public function getPedidosRecepcionados(PedidosFilterDTO $data): array {
		return $this->getPedidosList($data, true);
	}
}