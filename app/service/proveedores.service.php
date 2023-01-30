<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Plugins\OImage;
use OsumiFramework\App\Model\Proveedor;
use OsumiFramework\App\Model\ProveedorMarca;
use OsumiFramework\App\Model\Comercial;

class proveedoresService extends OService {
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
	 * Guarda una imagen en Base64. Si no tiene formato WebP se convierte
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param Proveedor $proveedor Marca a la que guardar la imagen
	 *
	 * @return void
	 */
	public function saveFoto(string $base64_string, Proveedor $proveedor): void {
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($proveedor->get('id')), $ext);
		$this->getLog()->debug('nueva foto: '.$ruta);
		$im = new OImage();
		$im->load($ruta);
		$this->getLog()->debug('foto cargada en oimage');
		// Compruebo tamaño inicial
		$this->getLog()->debug('tamaño inicial: '.$im->getWidth());
		if ($im->getWidth() > 1000) {
			$this->getLog()->debug('redimensiono');
			$im->resizeToWidth(1000);
			$im->save($ruta, $im->getImageType());
		}

		// Guardo la imagen ya modificada como WebP
		$im->save($proveedor->getRutaFoto(), IMAGETYPE_WEBP);
		$this->getLog()->debug('Guardo nueva imagen en '.$proveedor->getRutaFoto());

		// Borro la imagen temporal
		$this->getLog()->debug('borro imagen temporal');
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
			$pm = new ProveedorMarca();
			$pm->set('id_proveedor', $id_proveedor);
			$pm->set('id_marca',     $id_marca);
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
		$proveedor = new Proveedor();
		if ($proveedor->find(['id' => $id_proveedor])) {
			$proveedor->set('deleted_at', date('Y-m-d H:i:s', time()));
			$proveedor->save();

			$comerciales = $proveedor->getComerciales();
			foreach ($comerciales as $comercial) {
				$comercial->set('deleted_at', date('Y-m-d H:i:s', time()));
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
		$comercial = new Comercial();
		if ($comercial->find(['id' => $id_comercial])) {
			$comercial->set('deleted_at', date('Y-m-d H:i:s', time()));
			$comercial->save();

			return true;
		}
		return false;
	}
}
