<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\Marca;

class MarcasService extends OService {
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

		while ($res = $db->next()) {
			$list[] = Marca::from($res);
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
		OTools::checkOfw('tmp');
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($marca->id), $ext);
		$im = new OImage();
		$im->load($ruta);

		// Compruebo tamaño inicial
		if ($im->getWidth() > 1000) {
			$im->resizeToWidth(1000);
			$im->save($ruta, $im->getImageType());
		}

		// Guardo la imagen ya modificada como WebP
		$im->save($marca->getRutaFoto(), IMAGETYPE_WEBP);

		// Borro la imagen temporal
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
		$marca = Marca::findOne(['id' => $id_marca]);
		if (!is_null($marca)) {
			$marca->deleted_at = date('Y-m-d H:i:s', time());
			$marca->save();

			$db = new ODB();
			$sql = "DELETE FROM `proveedor_marca` WHERE `id_marca` = ?";
			$db->query($sql, [$id_marca]);

			return true;
		}
		return false;
	}
}
