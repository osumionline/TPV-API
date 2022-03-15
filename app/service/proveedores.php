<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\Proveedor;
use OsumiFramework\App\Model\ProveedorMarca;

class proveedoresService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

  /**
	 * Devuelve la lista completa de proveedores
	 *
	 * @return array Lista de proveedores
	 */
	public function getProveedores(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `proveedor` WHERE `deleted_at` IS NULL ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$proveedor = new Proveedor();
			$proveedor->update($res);
			array_push($list, $proveedor);
		}

		return $list;
	}

	/**
	 * Actualiza la lista de marcas de un proveedor
	 *
	 * @param int $id_proveedor Id del proveedor a actualizar
	 *
	 * @param array $marcas Lista nueva de marcas del proveedor
	 *
	 * @return void
	 */
	public function updateProveedoresMarcas(int $id_proveedor, array $marcas): void {
		$db = new ODB();
		$sql = "DELETE FROM `proveedor_marca` WHERE `id_proveedor` = ?";
		$db->query($sql, [$id_proveedor]);

		foreach ($marcas as $id_marca) {
			$pm = new ProveedorMarca();
			$pm->set('id_proveedor', $id_proveedor);
			$pm->set('id_marca',     $id_marca);
			$pm->save();
		}
	}
}
