<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Plugins\OImage;
use OsumiFramework\App\Model\Marca;

class marcasService extends OService {
	function __construct() {
		$this->loadService();
	}

	/**
	 * Devuelve la lista completa de marcas
	 *
	 * @return array Lista de marcas
	 */
	public function getMarcas(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `marca` WHERE `deleted_at` IS NULL ORDER BY `nombre`";
		$db->query($sql);
		$list = [];

		while ($res=$db->next()) {
			$marca = new Marca();
			$marca->update($res);
			array_push($list, $marca);
		}

		return $list;
	}

	/**
	 * Función para comprobar si un nombre de marca ya existe
	 *
	 * @param string $nombre Nombre de la marca a comprobar
	 *
	 * @return bool Indica si la marca ya existe o no
	 */
	public function checkNombreMarca(string $nombre): bool {
		$db = new ODB();
		$sql = "SELECT * FROM `marca` WHERE `deleted_at` IS NULL AND `nombre` = ? ORDER BY `nombre`";
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
	 * @param Marca $marca Marca a la que guardar la imagen
	 *
	 * @return void
	 */
	public function saveFoto(string $base64_string, Marca $marca): void {
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($marca->get('id')), $ext);
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
		$im->save($marca->getRutaFoto(), IMAGETYPE_WEBP);
		$this->getLog()->debug('Guardo nueva imagen en '.$marca->getRutaFoto());

		// Borro la imagen temporal
		$this->getLog()->debug('borro imagen temporal');
		unlink($ruta);
	}

	/**
	 * Función para borrar una marca
	 *
	 * @param int $id_marca Id de la marca a borrar
	 *
	 * @return bool Devuelve si la marca se ha encontrado y la operación ha sido correcta
	 */
	public function deleteMarca(int $id_marca): bool {
		$marca = new Marca();
		if ($marca->find(['id' => $id_marca])) {
			$marca->set('deleted_at', date('Y-m-d H:i:s', time()));
			$marca->save();

			$db = new ODB();
			$sql = "DELETE FROM `proveedor_marca` WHERE `id_marca` = ?";
			$db->query($sql, [$id_marca]);

			return true;
		}
		return false;
	}
}
