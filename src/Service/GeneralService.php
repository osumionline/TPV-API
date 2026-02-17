<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OImage;
use Osumi\OsumiFramework\App\Model\TipoPago;
use Osumi\OsumiFramework\App\Model\Caja;
use Osumi\OsumiFramework\App\Model\Empleado;
use Osumi\OsumiFramework\App\Model\EmpleadoRol;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\PagoCaja;
use Osumi\OsumiFramework\App\DTO\InstallationDTO;
use Osumi\OsumiFramework\App\Utils\AppData;

class GeneralService extends OService {
	/**
	 * Devuelve si la caja está abierta o no
	 *
	 * @return bool Devuelve si la caja está abierta o no para la fecha indicada
	 */
	public function getOpened(): bool {
		$c = $this->getCaja();
		if (is_null($c)) {
			return false;
		}
		if (is_null($c->cierre)) {
			return true;
		}
		return false;
	}

	/**
	 * Función para obtener la caja de una fecha
	 *
	 * @return Caja Caja obtenida o null si no existe
	 */
	public function getCaja(): ?Caja {
		$db = new ODB();
		$sql = "SELECT * FROM `caja` WHERE `cierre` IS NULL";
		$db->query($sql);

		if ($res = $db->next()) {
			return Caja::from($res);
		}
		return null;
	}

	/**
	 * Devuelve los datos de configuración generales de la aplicación como un JSON
	 *
	 * @return string Datos de configuración generales
	 */
	public function getAppData(): string {
		OTools::checkOfw('cache');
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
		OTools::checkOfw('cache');
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';

		// Logo (si viene una imagen en lugar de datos, lo ignoro)
		if (!str_starts_with($data->logo, 'http')) {
			$ext  = OImage::getImageExtension($data->logo);
			// Creo una imagen temporal con lo que haya mandado
			$ruta = OImage::saveImage($this->getConfig()->getDir('public'), $data->logo, 'logo_temp', $ext);
			$ruta_def = $this->getConfig()->getDir('public') . 'logo.jpg';

			// Si se ha guardado correctamente, convierto la imagen que sea a formato jpg (a no ser que ya sea jpg)
			if ($ext === 'jpg' || $ext === 'jpeg') {
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
			$empleado = Empleado::create();
			$empleado->nombre = $data->nombreEmpleado;
			$empleado->pass   = password_hash($data->pass, PASSWORD_BCRYPT);
			$empleado->color  = str_ireplace('#', '', $data->color);
			$empleado->save();

			// Le asigno todos los permisos posibles
			for ($i = 1; $i <= 24; $i++) {
				$empleado_rol = EmpleadoRol::create();
				$empleado_rol->id_empleado = $empleado->id;
				$empleado_rol->id_rol      = $i;
				$empleado_rol->save();
			}
		}

		// Compruebo si ya hay cierres de caja, si no hay ninguno hago la caja inicial
		$num = Caja::count();
		if ($num === 0) {
			$caja = Caja::create();
			$caja->apertura             = date('Y-m-d H:i:s', time());
			$caja->cierre               = null;
			$caja->ventas               = 0;
			$caja->beneficios           = 0;
			$caja->venta_efectivo       = 0;
			$caja->operaciones_efectivo = 0;
			$caja->descuento_efectivo   = 0;
			$caja->venta_otros          = 0;
			$caja->operaciones_otros    = 0;
			$caja->descuento_otros      = 0;
			$caja->importe_pagos_caja   = 0;
			$caja->num_pagos_caja       = 0;
			$caja->importe_apertura     = $data->cajaInicial;
			$caja->importe_cierre       = 0;
			$caja->importe_cierre_real  = 0;
			$caja->importe_retirado     = 0;

			$caja->save();
		}

		// Guardo datos de configuración
		$app_data = new AppData();
		$app_data->fromDTO($data);
		file_put_contents($app_data_file, $app_data->getArray(true));
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
			$list[] = TipoPago::from($res);
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
		$tp = TipoPago::findOne(['id' => $id_tipo_pago]);
		if (!is_null($tp)) {
			$tp->deleted_at = date('Y-m-d H:i:s', time());
			$tp->save();

			return true;
		}
		return false;
	}

	/**
	 * Función para obtener el orden de un nuevo tipo de pago
	 *
	 * @return int Orden del nuevo tipo de pago
	 */
	public function getNewTipoPagoOrden(): int {
		$db = new ODB();
		$sql = "SELECT MAX('orden') as `orden` FROM `tipo_pago`";
		$db->query($sql);
		$res = $db->next();

		return intval($res['orden']) + 1;
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
		OTools::checkOfw('tmp');
		$ext = OImage::getImageExtension($base64_string);
		$ruta = OImage::saveImage($this->getConfig()->getDir('ofw_tmp'), $base64_string, strval($tp->id), $ext);
		$im = new OImage();
		$im->load($ruta);
		// Compruebo tamaño inicial
		if ($im->getWidth() > 1000) {
			$im->resizeToWidth(1000);
			$im->save($ruta, $im->getImageType());
		}

		// Guardo la imagen ya modificada como WebP
		$im->save($tp->getRutaFoto(), IMAGETYPE_WEBP);

		// Borro la imagen temporal
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
		if (is_null($caja->cierre)) {
			$cierre = date('Y-m-d', time()).' 23:59:59';
		}
		else {
			$cierre = $caja->get('cierre', 'Y-m-d H:i:s');
		}
		$sql = "SELECT * FROM `pago_caja` WHERE `created_at` BETWEEN ? AND ?";
		$db->query($sql, [$caja->get('apertura', 'Y-m-d H:i:s'), $cierre]);
		$ret = [
			'importe' => 0,
			'num'     => 0
		];

		while ($res = $db->next()) {
			$pc = PagoCaja::from($res);
			$ret['importe'] += $pc->importe;
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
		if (is_null($caja->cierre)) {
			$cierre = date('Y-m-d', time()).' 23:59:59';
		}
		else {
			$cierre = $caja->get('cierre', 'Y-m-d H:i:s');
		}
		$sql = "SELECT * FROM `venta` WHERE `created_at` BETWEEN ? AND ?";
		$db->query($sql, [$caja->get('apertura', 'Y-m-d H:i:s'), $cierre]);
		$list = [];

		while ($res = $db->next()) {
			$list[] = Venta::from($res);
		}

		$tipos_pago = $this->getTiposPago();

		$ret = [
			'ventas'               => 0,
			'beneficios'           => 0,
			'venta_efectivo'       => 0,
			'operaciones_efectivo' => 0,
			'descuento_efectivo'   => 0,
			'venta_otros'          => 0,
			'operaciones_otros'    => 0,
			'descuento_otros'      => 0,
			'tipos_pago'           => []
		];
		foreach ($tipos_pago as $tp) {
			$ret['tipos_pago']['tipo_pago_'.$tp->id] = [
				'id'                => $tp->id,
				'operaciones'       => 0,
				'importe_total'     => 0,
				'importe_descuento' => 0
			];
		}
		foreach ($list as $venta) {
			$ret['ventas']         += $venta->total;
			$ret['beneficios']     += $venta->getBeneficio();
			$ret['venta_efectivo'] += $venta->getVentaEfectivo();
			$ret['venta_otros']    += $venta->getVentaOtros();
			if ($venta->getVentaEfectivo() !== 0) {
				$ret['operaciones_efectivo']++;
				$ret['descuento_efectivo'] += $venta->getVentaDescuento();
			}
			if ($venta->getVentaOtros() !== 0) {
				$ret['operaciones_otros']++;
				$ret['descuento_otros'] += $venta->getVentaDescuento();
			}
			if (!is_null($venta->id_tipo_pago)) {
				$ret['tipos_pago']['tipo_pago_'.$venta->id_tipo_pago]['operaciones']++;
				$ret['tipos_pago']['tipo_pago_'.$venta->id_tipo_pago]['importe_total']     += $venta->getVentaOtros();
				$ret['tipos_pago']['tipo_pago_'.$venta->id_tipo_pago]['importe_descuento'] += $venta->getVentaDescuento();;
			}
		}

		return $ret;
	}

	/**
	 * Obtiene el listado de salidas de caja de una fecha o un rango dados
	 *
	 * @param string $modo Indica si se deben obtener las salidas de caja de un día ("fecha") o entre dos fechas ("rango")
	 *
	 * @param string $fecha Indica la fecha de la que obtener las salidas de caja si el modo es "fecha"
	 *
	 * @param string $desde Indica la fecha inicial del rango del que obtener las salidas de caja en el modo "rango"
	 *
	 * @param string $hasta Indica la fecha final del rango del que obtener las salidas de caja en el modo "rango"
	 *
	 * @return array Lista de salidas de caja obtenidas
	 */
	public function getSalidasCaja(string $modo, ?string $fecha, ?string $desde, ?string $hasta): array {
		$db = new ODB();
		if ($modo == 'fecha') {
			$sql = "SELECT * FROM `pago_caja` WHERE DATE_FORMAT(`created_at`, '%d/%m/%Y') = ? ORDER BY `created_at` DESC";
			$db->query($sql, [$fecha]);
		}
		if ($modo == 'rango') {
			$sql = "SELECT * FROM `pago_caja` WHERE `created_at` BETWEEN STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') AND STR_TO_DATE(?,'%d/%m/%Y %H:%i:%s') ORDER BY `created_at` DESC";
			$db->query($sql, [$desde.' 00:00:00', $hasta.' 23:59:59']);
		}
		$ret = [];

		while ($res = $db->next()) {
			$ret[] = PagoCaja::from($res);
		}

		return $ret;
	}
}
