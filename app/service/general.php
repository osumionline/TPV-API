<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\App\Model\TipoPago;
use OsumiFramework\App\Model\Caja;
use OsumiFramework\App\Model\Empleado;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\DTO\InstallationDTO;

class generalService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
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
		if (file_exists($app_data_file)) {
			$data = json_decode(file_get_contents($app_data_file), true);
			return json_encode([
				'nombre'      => $data['nombre'],
				'tipoIva'     => $data['tipoIva'],
				'ivaList'     => $data['ivaList'],
				'reList'      => $data['reList'],
				'marginList'  => $data['marginList'],
				'ventaOnline' => $data['ventaOnline'],
				'fechaCad'    => $data['fechaCad'],
				'empleados'   => $data['empleados']
			]);
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
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';

		$app_data = [
			'nombre'      => $data->getNombre(),
			'cif'         => $data->getCif(),
			'telefono'    => $data->getTelefono(),
			'direccion'   => $data->getDireccion(),
			'email'       => $data->getEmail(),
			'twitter'     => $data->getTwitter(),
			'facebook'    => $data->getFacebook(),
			'instagram'   => $data->getInstagram(),
			'web'         => $data->getWeb(),
			'tipoIva'     => $data->getTipoIva(),
			'ivaList'     => $data->getIvaList(),
			'reList'      => $data->getReList(),
			'marginList'  => $data->getMarginList(),
			'ventaOnline' => $data->getVentaOnline(),
			'urlApi'      => $data->getUrlApi(),
			'fechaCad'    => $data->getFechaCad(),
			'empleados'   => $data->getEmpleados()
		];

		$data_str = json_encode($app_data);

		file_put_contents($app_data_file, $data_str);

		$empleado = new Empleado();
		$empleado->set('nombre', $data->getNombreEmpleado());
		$empleado->set('pass', password_hash($data->getPass(), PASSWORD_BCRYPT));
		$empleado->set('color', str_ireplace('#', '', $data->getColor()));
		$empleado->save();
	}

	/**
	 * Obtiene la lista de tipos de pago alternativos
	 *
	 * @return array Lista de tipos de pago
	 */
	public function getTiposPago(): array {
		$db = new ODB();
		$sql = "SELECT * FROM `tipo_pago` ORDER BY `orden` ASC";
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
	 * Obtiene la cantidad sacada de la caja como pagos de caja en un período de tiempo indicado
	 *
	 * @param Caja $caja Caja de la que obtener el período a comprobar
	 *
	 * @return float Importe total sacado de caja en el período indicado
	 */
	public function getPagosCajaDia(Caja $caja): float {
		$db = new ODB();
		$sql = "SELECT * FROM `pago_caja` WHERE `created_at` BETWEEN ? AND ?";
		$db->query($sql, [$caja->get('apertura', 'Y-m-d H:i:s'), $caja->get('cierre', 'Y-m-d H:i:s')]);
		$importe = 0;

		while ($res = $db->next()) {
			$pc = new PagoCaja();
			$pc->update($res);
			$importe += $pc->get('importe');
		}

		return $importe;
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

		$ret = [
			'ventas' => 0,
			'beneficios' => 0,
			'venta_efectivo' => 0,
			'venta_otros' => 0
		];
		foreach ($list as $venta) {
			$ret['ventas'] += $venta->get('total');
			$ret['beneficios'] += $venta->getBeneficio();
			$ret['venta_efectivo'] += $venta->getVentaEfectivo();
			$ret['venta_otros'] += $venta->getVentaOtros();
		}

		return $ret;
	}
}
