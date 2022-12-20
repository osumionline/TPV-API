<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\DTO\PedidosFilterDTO;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Model\PdfPedido;
use OsumiFramework\OFW\Tools\OTools;

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
			$sql .= " AND `".($recepcionado ? 'fecha_recepcionado' : 'created_at')."` > STR_TO_DATE('".$data->getFechaDesde()." 00:00:00', '%d/%m/%Y %H:%i:%s')";
		}
		if (!is_null($data->getFechaHasta())) {
			$sql .= " AND `".($recepcionado ? 'fecha_recepcionado' : 'created_at')."` < STR_TO_DATE('".$data->getFechaHasta()." 23:59:59', '%d/%m/%Y %H:%i:%s')";
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
		$ret['pags'] = ceil($res['num'] / $c->getExtra('num_por_pag'));

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

	/*
	 * Función para borrar todas las líneas de un pedido. Se usa al guardar un pedido para borrar las líneas existentes y así guardar la última versión.
	 *
	 * @param int $id_pedido Id del pedido al que que borrar sus líneas
	 *
	 * @return void
	 */
	public function borrarLineasPedido(int $id_pedido): void {
		$db = new ODB();
		$sql = "DELETE FROM `linea_pedido` WHERE `id_pedido` = ?";
		$db->query($sql, [$id_pedido]);
	}

	/**
	 * Función para actualizar (guardar o borrar los PDFs adjuntos de un pedido.
	 *
	 * @param Pedido $pedido Pedido al que adjuntar los PDFs
	 *
	 * @param array $pdfs Lista de PDFs a guardar o borrar
	 *
	 * @return void
	 */
	public function updatePedidoPDFs(Pedido $pedido, array $pdfs): void {
		foreach ($pdfs as $pdf) {
			$pedido_pdf = new PdfPedido();

			if (is_null($pdf['id']) && !is_null($pdf['data'])) {
				$pedido_pdf->set('id_pedido', $pedido->get('id'));
				$pedido_pdf->set('nombre', urldecode($pdf['nombre']));
				$pedido_pdf->save();

				$ruta = $pedido_pdf->getFileRoute();
				$ruta_folder = $pedido_pdf->getFileFolder();

				if (!is_dir($ruta_folder)) {
					mkdir($ruta_folder, 0777, true);
				}

				OTools::base64ToFile($pdf['data'], $ruta);
			}
			if (!is_null($pdf['id']) && $pdf['deleted']) {
				$pedido_pdf->find(['id' => $pdf['id']]);
				$pedido_pdf->deleteFull();

				$ruta_folder = $pedido_pdf->getFileFolder();

				if (count(scandir($ruta_folder)) == 2) {
					rmdir($ruta_folder);
				}
			}
		}
	}
}
