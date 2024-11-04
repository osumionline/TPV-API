<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\Foto;
use Osumi\OsumiFramework\App\Model\ArticuloFoto;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\DTO\HistoricoArticuloDTO;
use Osumi\OsumiFramework\App\Model\HistoricoArticulo;

class ArticulosService extends OService {
	/**
	 * Devuelve un nuevo localizador aleatorio
	 *
	 * @return string Nuevo localizador
	 */
	public function getNewLocalizador(): string {
		$loc = date('y', time()) . str_pad(strval(rand(1, 9999)), 4, '0', STR_PAD_LEFT);
		$art = Articulo::findOne(['localizador' => $loc]);

		if (!is_null($art)) {
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
	public function checkNombre(string $name, ?int $id_articulo): array {
		$db = new ODB();
		if (!is_null($id_articulo)) {
			$sql = "SELECT * FROM `articulo` WHERE `slug` LIKE '".OTools::slugify($name)."' AND `id` != ? AND `deleted_at` IS NULL";
			$db->query($sql, [$id_articulo]);
		}
		else {
			$sql = "SELECT * FROM `articulo` WHERE `slug` LIKE '".OTools::slugify($name)."' AND `deleted_at` IS NULL";
			$db->query($sql);
		}
		if ($res = $db->next()) {
			$art = Articulo::from($res);

			return ['status' => 'nombre-used', 'message' => $art->getMarca()->nombre];
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
	public function checkReferencia(string $referencia, ?int $id_articulo): array {
		if (!empty($referencia)) {
			$db = new ODB();
			if (!is_null($id_articulo)) {
				$sql = "SELECT * FROM `articulo` WHERE `referencia` = ? AND `id` != ? AND `deleted_at` IS NULL";
				$db->query($sql, [$referencia, $id_articulo]);
			}
			else {
				$sql = "SELECT * FROM `articulo` WHERE `referencia` = ? AND `deleted_at` IS NULL";
				$db->query($sql, [$referencia]);
			}
			if ($res = $db->next()) {
				$art = Articulo::from($res);

				return ['status' => 'referencia-used', 'message' => $art->nombre.'/'.$art->getMarca()->nombre];
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
	public function checkCodigosBarras(array $list, ?int $id_articulo): array {
		$db = new ODB();
		foreach ($list as $cb) {
			if (!is_null($id_articulo)) {
				$sql = "SELECT * FROM `codigo_barras` WHERE `codigo_barras` = ? AND `id_articulo` != ?";
				$db->query($sql, [$cb['codigoBarras'], $id_articulo]);
			}
			else {
				$sql = "SELECT * FROM `codigo_barras` WHERE `codigo_barras` = ?";
				$db->query($sql, [$cb['codigoBarras']]);
			}
			if ($res = $db->next()) {
				$cod = CodigoBarras::from($res);
				$art = $cod->getArticulo();

				if (is_null($art->deleted_at)) {
					return ['status' => 'cb-used', 'message' => $cb['codigoBarras'] . '/' . $art->nombre . '/' . $art->getMarca()->nombre];
				}
			}
		}
		return ['status' => 'ok', 'message' => ''];
	}

	/**
	 * Función para limpiar los códigos de barras borrados
	 *
	 * @param int $id_articulo
	 */
	public function cleanCodigosDeBarras(int $id_articulo, array $checked): void {
		$db = new ODB();
		$sql = "DELETE FROM `codigo_barras` WHERE `id_articulo` = ? AND `id` NOT IN (".implode(",", $checked).")";
		$db->query($sql, [$id_articulo]);
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
			for ($i = 0; $i < count($parts); $i++) {
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
			$articulo = Articulo::from($res);
			$ret[] = $articulo;
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
			if (!array_key_exists($item->id_marca, $marcas)) {
				$marcas[$item->id_marca] = $item->getMarca();
			}
			$ret[] = [
				'localizador' => $item->localizador,
				'nombre'      => $item->nombre,
				'marca'       => $marcas[$item->id_marca]->nombre,
				'pvp'         => $item->pvp,
				'stock'       => $item->stock
			];
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
		$ruta = $dir . $id . '.' . $ext;

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
		OTools::checkOfw('tmp');
		$ext  = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($id), $ext);
		$im = new OImage();
		$im->load($ruta);

		// Compruebo tamaño inicial
		if ($im->getWidth() > 1000) {
			$im->resizeToWidth(1000);
			$im->save($ruta, $im->getImageType());
		}

		// Guardo la imagen ya modificada como WebP
		$im->save($this->getConfig()->getExtra('fotos') . '/' . $id . '.webp', IMAGETYPE_WEBP);

		// Borro la imagen temporal
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
		foreach ($fotos_list as $foto) {
			if ($foto['status'] === 'ok') {
				continue;
			}
			if ($foto['status'] === 'deleted') {
				$f = Foto::findOne(['id' => $foto['id']]);
				$sql = "DELETE FROM `articulo_foto` WHERE `id_articulo` = ? AND `id_foto` = ?";
				$db->query($sql, [$art->id, $f->id]);

				$f->deleteFull();
			}
			if ($foto['status'] === 'new') {
				$f = Foto::create();
				$f->save();

				$af = ArticuloFoto::create();
				$af->id_articulo = $art->id;
				$af->id_foto     = $f->id;
				$af->orden       = 0;
				$af->save();

				$foto['id'] = $f->id;
				$foto['status'] = 'ok';

				$this->saveFoto($foto['data'], $f->id);
			}
		}

		$cont = 0;
		foreach ($fotos_list as $f) {
			if ($f['status'] !== 'deleted') {
				$cont ++;
				$sql = "UPDATE `articulo_foto` SET `orden` = ? WHERE `id_articulo` = ? AND `id_foto` = ?";
				$db->query($sql, [$cont, $art->id, $f['id']]);
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
			$articulo = Articulo::from($res);

			$ret[] = [
				'id'             => $articulo->id,
				'acceso_directo' => $articulo->acceso_directo,
				'nombre'         => $articulo->nombre
			];
		}

		return $ret;
	}

	/**
	 * Función para calcular un margen de beneficio
	 *
	 * @param float $puc PUC de un artículo
	 *
	 * @param float $pvp PVP de un artículo
	 *
	 * @return float Margen de beneficio de un artículo
	 */
	public function getMargen(float $puc, float $pvp): float {
		if ($pvp === 0) {
      return 0;
    }
    return (100 * ($pvp - $puc)) / $pvp;
	}

	/**
	 * Función para obtener los datos históricos de un artículo
	 *
	 * @param HistoricoArticuloDTO $data Objeto con la información del artículo a buscar
	 *
	 * @return array Lista de resultados
	 */
	public function getHistoricoArticulo(HistoricoArticuloDTO $data): array {
		$db = new ODB();
		$sql = "SELECT * FROM `historico_articulo` WHERE `id_articulo` = ?";

		if (!is_null($data->order_by) && !is_null($data->order_sent)) {
			$order_field = '';
			switch ($data->order_by) {
				case "createdAt": { $order_field = 'created_at'; }
				break;
		    case "tipo": { $order_field = 'tipo'; }
				break;
		    case "stockPrevio": { $order_field = 'stock_previo'; }
				break;
		    case "diferencia": { $order_field = 'diferencia'; }
				break;
		    case "stockFinal": { $order_field = 'stock_final'; }
				break;
		    case "puc": { $order_field = 'puc'; }
				break;
		    case "pvp": { $order_field = 'pvp'; }
				break;
		    case "idVenta": { $order_field = 'id_venta'; }
				break;
		    case "idPedido": { $order_field = 'id_pedido'; }
				break;
			}
			$sql_limit = " ORDER BY `".$order_field."` ".strtoupper($data->order_sent);
		}
		else {
			$sql_limit = " ORDER BY `created_at` DESC";
		}
		if (!is_null($data->num)) {
			$lim = ($data->pagina - 1) * $data->num;
			$sql_limit .= " LIMIT ".$lim.",".$data->num;
		}

		$ret = [];
		$db->query($sql.$sql_limit, [$data->id]);

		while ($res = $db->next()) {
			$ret[] = HistoricoArticulo::from($res);
		}

		return $ret;
	}

	/**
	 * Función para obtener el número de páginas que tiene el histórico de un artículo concreto
	 *
	 * @param int $id_articulo Id del artículo del que obtener los datos
	 *
	 * @return int Número de páginas
	 */
	public function getHistoricoArticuloPags(int $id_articulo): int {
		return HistoricoArticulo::count(['id_articulo' => $id_articulo]);
	}
}
