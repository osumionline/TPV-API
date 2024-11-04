<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\Proveedor;
use Osumi\OsumiFramework\App\Model\ProveedorMarca;
use Osumi\OsumiFramework\App\Model\Comercial;

class ProveedoresService extends OService {
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

		while ($res = $db->next()) {
			$list[] = Proveedor::from($res);
		}

		return $list;
	}

	/**
	 * Función para comprobar si un nombre de proveedor ya existe
	 *
	 * @param string $nombre Nombre del proveedor a comprobar
	 *
	 * @return bool Indica si el proveedor ya existe o no
	 */
	public function checkNombreProveedor(string $nombre): bool {
		$db = new ODB();
		$sql = "SELECT * FROM `proveedor` WHERE `deleted_at` IS NULL AND `nombre` = ? ORDER BY `nombre`";
		$db->query($sql, [$nombre]);

		if ($res = $db->next()) {
			return true;
		}
		return false;
	}

	/**
	 * Guarda una imagen en Base64. Si no tiene formato WebP se convierte
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param Proveedor $proveedor Marca a la que guardar la imagen
	 *
	 * @return void
	 */
	public function saveFoto(string $base64_string, Proveedor $proveedor): void {
		OTools::checkOfw('tmp');
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($proveedor->id), $ext);
		$im = new OImage();
		$im->load($ruta);

		// Compruebo tamaño inicial
		if ($im->getWidth() > 1000) {
			$im->resizeToWidth(1000);
			$im->save($ruta, $im->getImageType());
		}

		// Guardo la imagen ya modificada como WebP
		$im->save($proveedor->getRutaFoto(), IMAGETYPE_WEBP);

		// Borro la imagen temporal
		unlink($ruta);
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
		$sql = "UPDATE `articulo` SET `id_proveedor` = NULL WHERE `id_proveedor` = ?";
		$db->query($sql, [$id_proveedor]);

		foreach ($marcas as $id_marca) {
			$pm = ProveedorMarca::create();
			$pm->id_proveedor = $id_proveedor;
			$pm->id_marca     = $id_marca;
			$pm->save();

			$sql = "UPDATE `articulo` SET `id_proveedor` = ? WHERE `id_marca` = ?";
			$db->query($sql, [$id_proveedor, $id_marca]);
		}
	}

	/**
	 * Función para borrar un proveedor
	 *
	 * @param int $id_proveedor Id del proveedor a borrar
	 *
	 * @return bool Devuelve si el proveedor se ha encontrado y la operación ha sido correcta
	 */
	public function deleteProveedor(int $id_proveedor): bool {
		$proveedor = Proveedor::findOne(['id' => $id_proveedor]);
		if (!is_null($proveedor)) {
			$proveedor->deleted_at = date('Y-m-d H:i:s', time());
			$proveedor->save();

			$comerciales = $proveedor->getComerciales();
			foreach ($comerciales as $comercial) {
				$comercial->deleted_at = date('Y-m-d H:i:s', time());
				$comercial->save();
			}

			$db = new ODB();
			$sql = "DELETE FROM `proveedor_marca` WHERE `id_proveedor` = ?";
			$db->query($sql, [$id_proveedor]);

			return true;
		}
		return false;
	}

	/**
	 * Función para borrar un comercial
	 *
	 * @param int $id_comercial Id del comercial a borrar
	 *
	 * @return bool Devuelve si el comercial se ha encontrado y la operación ha sido correcta
	 */
	public function deleteComercial(int $id_comercial): bool {
		$comercial = Comercial::findOne(['id' => $id_comercial]);
		if (!is_null($comercial)) {
			$comercial->deleted_at = date('Y-m-d H:i:s', time());
			$comercial->save();

			return true;
		}
		return false;
	}
}
