<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\DTO\PedidosFilterDTO;
use Osumi\OsumiFramework\App\Model\Pedido;
use Osumi\OsumiFramework\App\Model\PdfPedido;

class ComprasService extends OService {
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

		$ret = ['list' => [], 'pags' => 0];

		$sql_count = "SELECT COUNT(*) AS `num` FROM `pedido` WHERE `recepcionado` = ".($recepcionado ? '1' : '0');
		$sql_all = "SELECT * FROM `pedido` WHERE `recepcionado` = ".($recepcionado ? '1' : '0');
		$sql = "";

		if (!is_null($data->fecha_desde)) {
			$sql .= " AND `".($recepcionado ? 'fecha_recepcionado' : 'created_at')."` > STR_TO_DATE('".$data->fecha_desde." 00:00:00', '%d/%m/%Y %H:%i:%s')";
		}
		if (!is_null($data->fecha_hasta)) {
			$sql .= " AND `".($recepcionado ? 'fecha_recepcionado' : 'created_at')."` < STR_TO_DATE('".$data->fecha_hasta." 23:59:59', '%d/%m/%Y %H:%i:%s')";
		}
		if (!is_null($data->id_proveedor)) {
			$sql .= " AND `id_proveedor` = ".$data->id_proveedor;
		}
		if (!is_null($data->albaran)) {
			$sql .= " AND `albaran` LIKE '%".$data->albaran."%'";
		}
		if (!is_null($data->importe_desde)) {
			$sql .= " AND `importe` >= ".$data->importe_desde;
		}
		if (!is_null($data->importe_hasta)) {
			$sql .= " AND `importe` <= ".$data->importe_hasta;
		}
		$sql .= " ORDER BY `created_at` DESC";

		$db->query($sql_count.$sql);
		$res = $db->next();
		$ret['pags'] = ceil($res['num'] / $data->num);

		$lim = ($data->pagina - 1) * $data->num;
		$sql_pag = $sql_all.$sql;
		$sql_pag .= " LIMIT ".$lim.",".$data->num;

		$db->query($sql_pag);
		while ($res = $db->next()) {
			$ret['list'][] = Pedido::from($res);
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
				$pedido_pdf->id_pedido = $pedido->id;
				$pedido_pdf->nombre    = urldecode($pdf['nombre']);
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

	/*
	 * Función para borrar todas las líneas de la vista de un pedido. Se usa al guardar un pedido para borrar la vista existente y así guardar la última versión.
	 *
	 * @param int $id_pedido Id del pedido al que que borrar sus líneas
	 *
	 * @return void
	 */
	public function borrarVistaPedido(int $id_pedido): void {
		$db = new ODB();
		$sql = "DELETE FROM `vista_pedido` WHERE `id_pedido` = ?";
		$db->query($sql, [$id_pedido]);
	}
}
