<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Plugins\OImage;
use OsumiFramework\App\Model\TipoPago;
use OsumiFramework\App\Model\Caja;
use OsumiFramework\App\Model\Empleado;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\DTO\InstallationDTO;
use OsumiFramework\App\Utils\AppData;

class generalService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
		require $this->getConfig()->getDir('app_utils').'AppData.php';
	}

	/**
	 * Devuelve si la caja está abierta o no
	 *
	 * @param string Fecha a comprobar
	 *
	 * @return bool Devuelve si la caja está abierta o no para la fecha indicada
	 */
	public function getOpened(string $date): bool {
		$db = new ODB();
		$sql = "SELECT * FROM `caja` WHERE DATE(`apertura`) = ?";
		$db->query($sql, [$date]);

		if ($res = $db->next()) {
			return true;
		}
		else {
			return false;
		}
	}

	/**
	 * Devuelve los datos de configuración generales de la aplicación como un JSON
	 *
	 * @return string Datos de configuración generales
	 */
	public function getAppData(): string {
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if ($app_data->getLoaded()) {
			return $app_data->getArray();
		}
		else {
			return 'null';
		}
	}

	/**
	 * Guarda los datos de configuración generales de la aplicación
	 *
	 * @param InstallationDTO $data Información sobre la instalación
	 *
	 * @return void
	 */
	public function saveAppData(InstallationDTO $data): void {
		// Ruta del archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';

		// Logo (si viene una imagen en lugar de datos, lo ignoro)
		if (!str_starts_with($data->getLogo(), 'http')) {
			$ext  = OImage::getImageExtension($data->getLogo());
			// Creo una imagen temporal con lo que haya mandado
			$ruta = OImage::saveImage($this->getConfig()->getDir('web'), $data->getLogo(), 'logo_temp', $ext);
			$ruta_def = $this->getConfig()->getDir('web').'logo.jpg';

			// Si se ha guardado correctamente, convierto la imagen que sea a formato jpg (a no ser que ya sea jpg)
			if ($ext == 'jpg' || $ext == 'jpeg') {
				rename($ruta, $ruta_def);
			}
			else {
				if (file_exists($ruta)) {
					$im = new OImage();
					$im->load($ruta);
					$im->save($ruta_def, IMAGETYPE_JPEG);
					unlink($ruta);
				}
			}
		}

		// Empleado (si ya existe el archivo de configuración es que se están editando sus datos y no hay que crear ningún empleado nuevo)
		if (!file_exists($app_data_file)) {
			$empleado = new Empleado();
			$empleado->set('nombre', $data->getNombreEmpleado());
			$empleado->set('pass', password_hash($data->getPass(), PASSWORD_BCRYPT));
			$empleado->set('color', str_ireplace('#', '', $data->getColor()));
			$empleado->save();
		}

		// Guardo datos de configuración
		$app_data = new AppData();
		$app_data->fromDTO($data);
		file_put_contents($app_data_file, $app_data->getArray());
	}

	/**
	 * Obtiene la lista de tipos de pago alternativos
	 *
	 * @return array Lista de tipos de pago
	 */
	public function getTiposPago(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `tipo_pago` WHERE `deleted_at` IS NULL ORDER BY `orden` ASC";
		$db->query($sql);
		$list = [];

		while ($res = $db->next()) {
			$tp = new TipoPago();
			$tp->update($res);
			array_push($list, $tp);
		}

		return $list;
	}

	/**
	 * Función para marcar un tipo de pago como borrado
	 *
	 * @param int $id_tipo_pago Id del tipo de pago a borrar
	 *
	 * @return bool Devuelve si el tipo de pago se ha encontrado y la operación ha sido correcta
	 */
	public function deleteTipoPago(int $id_tipo_pago): bool {
		$tp = new TipoPago();
		if ($tp->find(['id' => $id_tipo_pago])) {
			$tp->set('deleted_at', date('Y-m-d H:i:s', time()));
			$tp->save();

			return true;
		}
		return false;
	}

	/**
	 * Guarda una imagen en Base64. Si no tiene formato WebP se convierte
	 *
	 * @param string $base64_string Imagen en formato Base64
	 *
	 * @param TipoPago $tp Tipo de pago al que guardar la imagen
	 *
	 * @return void
	 */
	public function saveFoto(string $base64_string, TipoPago $tp): void {
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($tp->get('id')), $ext);
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
		$im->save($tp->getRutaFoto(), IMAGETYPE_WEBP);
		$this->getLog()->debug('Guardo nueva imagen en '.$tp->getRutaFoto());

		// Borro la imagen temporal
		$this->getLog()->debug('borro imagen temporal');
		unlink($ruta);
	}

	/**
	 * Obtiene la cantidad sacada de la caja como pagos de caja en un período de tiempo indicado
	 *
	 * @param Caja $caja Caja de la que obtener el período a comprobar
	 *
	 * @return array Importe total y número de retiradas de caja en el período indicado
	 */
	public function getPagosCajaDia(Caja $caja): array {
		$db = new ODB();
		$sql = "SELECT * FROM `pago_caja` WHERE `created_at` BETWEEN ? AND ?";
		$db->query($sql, [$caja->get('apertura', 'Y-m-d H:i:s'), $caja->get('cierre', 'Y-m-d H:i:s')]);
		$ret = [
			'importe' => 0,
			'num' => 0
		];

		while ($res = $db->next()) {
			$pc = new PagoCaja();
			$pc->update($res);
			$ret['importe'] += $pc->get('importe');
			$ret['num']++;
		}

		return $ret;
	}

	/**
	 * Obtiene información de ventas y beneficios correspondiente a una apertura/cierre de caja
	 *
	 * @param Caja $caja Caja de la que obtener las ventas y beneficios
	 *
	 * @return array Array con la información de ventas y beneficios
	 */
	public function getVentasDia(Caja $caja): array {
		$db = new ODB();
		$sql = "SELECT * FROM `venta` WHERE `created_at` BETWEEN ? AND ?";
		$db->query($sql, [$caja->get('apertura', 'Y-m-d H:i:s'), $caja->get('cierre', 'Y-m-d H:i:s')]);
		$list = [];

		while ($res = $db->next()) {
			$venta = new Venta();
			$venta->update($res);
			array_push($list, $venta);
		}

		$tipos_pago = $this->getTiposPago();

		$ret = [
			'ventas' => 0,
			'beneficios' => 0,
			'venta_efectivo' => 0,
			'operaciones_efectivo' => 0,
			'descuento_efectivo' => 0,
			'venta_otros' => 0,
			'operaciones_otros' => 0,
			'descuento_otros' => 0,
			'tipos_pago' => []
		];
		foreach ($tipos_pago as $tp) {
			$ret['tipos_pago']['tipo_pago_'.$tp->get('id')] = [
				'id' => $tp->get('id'),
				'operaciones' => 0,
				'importe_total' => 0,
				'importe_descuento' => 0
			];
		}
		foreach ($list as $venta) {
			$ret['ventas'] += $venta->get('total');
			$ret['beneficios'] += $venta->getBeneficio();
			$ret['venta_efectivo'] += $venta->getVentaEfectivo();
			$ret['venta_otros'] += $venta->getVentaOtros();
			if ($venta->getVentaEfectivo() != 0) {
				$ret['operaciones_efectivo']++;
				$ret['descuento_efectivo']+= $venta->getVentaDescuento();
			}
			if ($venta->getVentaOtros() != 0) {
				$ret['operaciones_otros']++;
				$ret['descuento_otros']+= $venta->getVentaDescuento();
			}
			if (!is_null($venta->get('id_tipo_pago'))) {
				$ret['tipos_pago']['tipo_pago_'.$venta->get('id_tipo_pago')]['operaciones']++;
				$ret['tipos_pago']['tipo_pago_'.$venta->get('id_tipo_pago')]['importe_total'] += $venta->getVentaOtros();
				$ret['tipos_pago']['tipo_pago_'.$venta->get('id_tipo_pago')]['importe_descuento'] += $venta->getVentaDescuento();;
			}
		}

		return $ret;
	}
}
