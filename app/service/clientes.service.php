<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\Factura;

class clientesService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Devuelve la lista completa de clientes
	 *
	 * @return array Lista de clientes
	 */
	public function getClientes(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `cliente` WHERE `deleted_at` IS NULL ORDER BY `nombre_apellidos`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$cliente = new Cliente();
			$cliente->update($res);
			array_push($list, $cliente);
		}

		return $list;
	}

  /**
	 * Busca entre los clientes
	 *
	 * @param string $name Nombre a buscar
	 *
	 * @return array Lista de marcas
	 */
	public function searchClientes(string $name): array {
		$db = new ODB();
		$sql = "SELECT * FROM `cliente` WHERE LOWER(CONCAT(`nombre_apellidos`, ' ', COALESCE(`telefono`, ''), ' ', COALESCE(`email`, ''))) LIKE '%".strtolower($name)."%' AND `deleted_at` IS NULL ORDER BY `nombre_apellidos`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$cliente = new Cliente();
			$cliente->update($res);
			array_push($list, $cliente);
		}

		return $list;
	}

	/**
	 * Obtiene la lista de últimos artículos vendidos a un cliente
	 *
	 * @param int $id_cliente Id del cliente del que buscar las ventas
	 *
	 * @return array Lista de últimos artículos vendidos
	 */
	public function getUltimasVentas(int $id_cliente): array {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `id_cliente` = ? ORDER BY `created_at` DESC";
		$db->query($sql, [$id_cliente]);
		$list = [];

		while ($res = $db->next()) {
			$venta = new Venta();
			$venta->update($res);

			$lineas = $venta->getLineas();
			foreach ($lineas as $linea) {
				array_push($list, [
					'fecha' => $linea->get('created_at', 'd/m/Y'),
					'localizador' => $linea->getArticulo()->get('localizador'),
					'nombre' => $linea->getArticulo()->get('nombre'),
					'unidades' => $linea->get('unidades'),
					'pvp' => $linea->get('pvp'),
					'importe' => $linea->get('importe')
				]);
			}
		}

		return $list;
	}

	/**
	 * Obtiene la lista de los artículos más vendidos a un cliente
	 *
	 * @param int $id_cliente Id del cliente del que buscar los artículos más vendidos
	 *
	 * @return array Lista de los artículos más vendidos
	 */
	public function getTopVentas(int $id_cliente): array {
		$lineas = $this->getUltimasVentas($id_cliente);
		$list = [];

		foreach ($lineas as $linea) {
			if (!array_key_exists($linea['localizador'], $list)) {
				$list[$linea['localizador']] = [
					'localizador' => $linea['localizador'],
					'nombre' => $linea['nombre'],
					'importe' => 0
				];
			}
			$list[$linea['localizador']]['importe'] += $linea['importe'];
		}
		array_multisort(array_column($list, 'importe'), $list);

		return $list;
	}

	/**
	 * Función para borrar un cliente y limpiar sus ventas asociadas
	 *
	 * @param int $id_cliente Id del cliente a borrar
	 *
	 * @return bool Devuelve si el cliente se ha encontrado y la operación ha sido correcta
	 */
	public function deleteCliente(int $id_cliente): bool {
		$cliente = new Cliente();
		if ($cliente->find(['id' => $id_cliente])) {
			$cliente->set('deleted_at', date('Y-m-d H:i:s', time()));
			$cliente->save();
			return true;
		}
		return false;
	}

	/**
	 * Función para obtener la lista de facturas de un cliente
	 *
	 * @param int $id_cliente Id del cliente
	 *
	 * @return array Lista de facturas de un cliente
	 */
	public function getFacturasCliente(int $id_cliente): array {
		$db = new ODB();
		$sql = "SELECT * FROM `factura` WHERE `id_cliente` = ? ORDER BY `created_at` DESC";
		$db->query($sql, [$id_cliente]);
		$list = [];

		while ($res = $db->next()) {
			$factura = new Factura();
			$factura->update($res);
			array_push($list, $factura);
		}

		return $list;
	}

	/**
	 * Función para obtener la lista de ventas de un cliente
	 *
	 * @param int $id_cliente Id del cliente
	 *
	 * @param string $facturadas Sirve para elegir las ventas ya facturadas ("si"), las no facturadas ("no") o todas ("todas")
	 *
	 * @return array Lista de ventas de un cliente
	 */
	public function getVentasCliente(int $id_cliente, string $facturadas): array {
		$db = new ODB();
		$list = [];

		switch ($facturadas) {
			case 'si': {
				$sql = "SELECT * FROM `venta` WHERE `id_cliente` = ? AND `id` IN (SELECT `id_venta` FROM `factura_venta` WHERE `id_factura` IN (SELECT `id` FROM `factura` WHERE `id_cliente` = ?)) ORDER BY `created_at` DESC";
				$db->query($sql, [$id_cliente, $id_cliente]);
			}
			break;
			case 'no': {
				$sql = "SELECT * FROM `venta` WHERE `id_cliente` = ? AND `id` NOT IN (SELECT `id_venta` FROM `factura_venta` WHERE `id_factura` IN (SELECT `id` FROM `factura` WHERE `id_cliente` = ?)) ORDER BY `created_at` DESC";
				$db->query($sql, [$id_cliente, $id_cliente]);
			}
			break;
			case 'todas': {
				$sql = "SELECT * FROM `venta` WHERE `id_cliente` = ? ORDER BY `created_at` DESC";
				$db->query($sql, [$id_cliente]);
			}
			break;
		}

		while ($res = $db->next()) {
			$venta = new Venta();
			$venta->update($res);
			array_push($list, $venta);
		}

		return $list;
	}
}
