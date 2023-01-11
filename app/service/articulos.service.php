<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\OFW\Plugins\OImage;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\Foto;
use OsumiFramework\App\Model\ArticuloFoto;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\LineaPedido;

class articulosService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}

	/**
	 * Devuelve un nuevo localizador aleatorio
	 *
	 * @return string Nuevo localizador
	 */
	public function getNewLocalizador(): string {
		$loc = date('y', time()) . str_pad(strval(rand(1, 9999)), 4, '0', STR_PAD_LEFT);
		$art = new Articulo();

		if ($art->find(['localizador' => $loc])) {
			return $this->getNewLocalizador();
		}
		else {
			return $loc;
		}
	}

	/**
	 * Función para comprobar si un nombre de artículo ya está usado
	 *
	 * @param string $name Nombre de artículo a comprobar
	 *
	 * @param int $id_articulo Articulo al que pertenece el nombre, para descartarlo
	 *
	 * @return array Resultado de la comprobación
	 */
	public function checkNombre(string $name, int $id_articulo): array {
		$db = new ODB();
		$sql = "SELECT * FROM `articulo` WHERE `slug` LIKE '%".OTools::slugify($name)."%' AND `id` != ? AND `deleted_at` IS NULL";
		$db->query($sql, [$id_articulo]);
		if ($res = $db->next()) {
			$art = new Articulo();
			$art->update($res);

			return ['status' => 'nombre-used', 'message' => $art->getMarca()->get('nombre')];
		}
		return ['status' => 'ok', 'message' => ''];
	}

	/**
	 * Función para comprobar si una referencia de artículo ya está usado
	 *
	 * @param string $referencia Referencia de un artículo a comprobar
	 *
	 * @param int $id_articulo Articulo al que pertenece la referencia, para descartarlo
	 *
	 * @return array Resultado de la comprobación
	 */
	public function checkReferencia(string $referencia, int $id_articulo): array {
		if (!empty($referencia)) {
			$db = new ODB();
			$sql = "SELECT * FROM `articulo` WHERE `referencia` = ? AND `id` != ? AND `deleted_at` IS NULL";
			$db->query($sql, [$referencia, $id_articulo]);
			if ($res = $db->next()) {
				$art = new Articulo();
				$art->update($res);

				return ['status' => 'referencia-used', 'message' => $art->get('nombre').'/'.$art->getMarca()->get('nombre')];
			}
		}
		return ['status' => 'ok', 'message' => ''];
	}

	/**
	 * Función para comprobar si un código de barras de un artículo ya está usado
	 *
	 * @param array $list Lista de códigos de barras a comprobar
	 *
	 * @param int $id_articulo Articulo al que pertenece el código de barras, para descartarlo
	 *
	 * @return array Resultado de la comprobación
	 */
	public function checkCodigosBarras(array $list, int $id_articulo): array {
		$db = new ODB();
		foreach ($list as $cb) {
			$sql = "SELECT * FROM `codigo_barras` WHERE `codigo_barras` = ? AND `id_articulo` != ?";
			$db->query($sql, [$cb['codigoBarras'], $id_articulo]);
			if ($res = $db->next()) {
				$cod = new CodigoBarras();
				$cod->update($res);
				$art = $cod->getArticulo();

				return ['status' => 'cb-used', 'message' => $cb['codigoBarras'].'/'.$art->get('nombre').'/'.$art->getMarca()->get('nombre')];
			}
		}
		return ['status' => 'ok', 'message' => ''];
	}

	/**
	 * Busca entre los artículos existentes
	 *
	 * @param string $name Nombre del artículo a buscar
	 *
	 * @param int $id_marca Id de la marca en la que buscar
	 *
	 * @return array Lista de artículos encontrados
	 */
	public function searchArticulos(string $name, int $id_marca): array {
		$db = new ODB();
		$sql = "SELECT * FROM `articulo` WHERE `deleted_at` IS NULL";
		$ret = [];

		if (!empty($name)) {
			$parts = explode(' ', $name);
			for ($i = 0; $i<count($parts); $i++) {
				$parts[$i] = OTools::slugify($parts[$i]);
			}
			$sql .= " AND `slug` LIKE '%".implode('%', $parts)."%'";
		}
		if ($id_marca !== -1) {
			$sql .= " AND `id_marca` = ".$id_marca;
		}
		$sql .= " ORDER BY `nombre` ASC";
		$db->query($sql);

		while ($res = $db->next()) {
			$articulo = new Articulo();
			$articulo->update($res);
			array_push($ret, $articulo);
		}

		return $ret;
	}

	/**
	 * Buscador para el apartado de ventas
	 *
	 * @param string $name Nombre del artículo a buscar
	 *
	 * @return array Lista de artículos encontrados
	 */
	public function searchArticulosVentas(string $name): array {
		$list = $this->searchArticulos($name, -1);
		$ret = [];
		$marcas = [];

		foreach ($list as $item) {
			if (!array_key_exists($item->get('id_marca'), $marcas)) {
				$marcas[$item->get('id_marca')] = $item->getMarca();
			}
			array_push($ret, [
				'localizador' => $item->get('localizador'),
				'nombre' => $item->get('nombre'),
				'marca'  => $marcas[$item->get('id_marca')]->get('nombre'),
				'pvp'    => $item->get('pvp'),
				'stock'  => $item->get('stock')
			]);
		}

		return $ret;
	}

	/**
	 * Obtener la extensión de una foto en formato Base64
	 *
	 * @param string $data Imagen en formato Base64
	 *
	 * @return string Extensión de la imagen
	 */
	public function getFotoExt(string $data): string {
		$arr_data = explode(';', $data);
		$arr_data = explode(':', $arr_data[0]);
		$arr_data = explode('/', $arr_data[1]);

		return $arr_data[1];
	}

	/**
	 * Guarda una imagen en Base64 en la ubicación indicada
	 *
	 * @param string $dir Ruta en la que guardar la imagen
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param int $id Id de la imagen
	 *
	 * @param string $ext Extensión del archivo de imagen
	 *
	 * @return string Devuelve la ruta completa a la nueva imagen
	 */
	public function saveImage(string $dir, string $base64_string, int $id, string $ext): string {
		$ruta = $dir.$id.'.'.$ext;

		if (file_exists($ruta)) {
			unlink($ruta);
		}

		$ifp = fopen($ruta, "wb");
		$data = explode(',', $base64_string);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);

		return $ruta;
	}

	/**
	 * Guarda una imagen en Base64. Si no tiene formato WebP se convierte
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param int $id Id de la imagen
	 *
	 * @return void
	 */
	public function saveFoto(string $base64_string, int $id): void {
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($id), $ext);
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
		$im->save($this->getConfig()->getExtra('fotos').'/'.$id.'.webp', IMAGETYPE_WEBP);
		$this->getLog()->debug('Guardo nueva imagen en '.$this->getConfig()->getExtra('fotos').'/'.$id.'.webp');

		// Borro la imagen temporal
		$this->getLog()->debug('borro imagen temporal');
		unlink($ruta);
	}

	/**
	 * Actualiza las fotos de un artículo
	 *
	 * @param Articulo $art Artículo al que actualizar las fotos
	 *
	 * @param array $fotos_list Lista de fotos a guardar
	 *
	 * @return void
	 */
	public function updateFotos(Articulo $art, array $fotos_list): void {
		$db = new ODB();
		$this->getLog()->debug('Articulo: '.$art->get('id').' - fotos: '.count($fotos_list));
		foreach ($fotos_list as $foto) {
			$this->getLog()->debug('status: '.$foto['status'].' - id: '.$foto['id']);
			if ($foto['status'] == 'ok') {
				continue;
			}
			if ($foto['status'] == 'deleted') {
				$f = new Foto();
				$f->find(['id' => $foto['id']]);
				$sql = "DELETE FROM `articulo_foto` WHERE `id_articulo` = ? AND `id_foto` = ?";
				$db->query($sql, [$art->get('id'), $f->get('id')]);

				$f->deleteFull();
			}
			if ($foto['status'] == 'new') {
				$f = new Foto();
				$f->save();

				$af = new ArticuloFoto();
				$af->set('id_articulo', $art->get('id'));
				$af->set('id_foto', $f->get('id'));
				$af->set('orden', 0);
				$af->save();

				$this->getLog()->debug('nueva foto: '.$f->get('id'));
				$foto['id'] = $f->get('id');
				$foto['status'] = 'ok';

				$this->saveFoto($foto['data'], $f->get('id'));
			}
		}

		$cont = 0;
		foreach ($fotos_list as $f) {
			if ($f['status'] != 'deleted') {
				$cont ++;
				$this->getLog()->debug('Foto: '.$f['id']);
				$this->getLog()->debug('Orden: '.$cont);
				$sql = "UPDATE `articulo_foto` SET `orden` = ? WHERE `id_articulo` = ? AND `id_foto` = ?";
				$db->query($sql, [$cont, $art->get('id'), $f['id']]);
			}
		}
	}

	/**
	 * Función para obtener la lista de accesos directos
	 *
	 * @return array Lista de accesos directos
	 */
	public function getAccesosDirectos(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `articulo` WHERE `acceso_directo` IS NOT NULL ORDER BY `acceso_directo` ASC";
		$db->query($sql);
		$ret = [];

		while ($res = $db->next()) {
			$articulo = new Articulo();
			$articulo->update($res);

			array_push($ret, [
				'id' => $articulo->get('id'),
				'acceso_directo' => $articulo->get('acceso_directo'),
				'nombre' => $articulo->get('nombre')
			]);
		}

		return $ret;
	}
}
