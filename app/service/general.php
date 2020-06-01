<?php declare(strict_types=1);
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
		$app_data_file = $this->getConfig()->getDir('app_cache').'app_data.json';
		if (file_exists($app_data_file)) {
			return file_get_contents($app_data_file);
		}
		else {
			return 'null';
		}
	}

	/**
	 * Guarda los datos de configuración generales de la aplicación
	 *
	 * @param string $tipo_iva Tipo de IVA (iva IVA - re Recargo de equivalencia)
	 *
	 * @param array $iva_list Lista de posibles valores de IVA
	 *
	 * @param array $margin_list Lista de posibles márgenes de beneficio
	 *
	 * @param bool $venta_online Indica si está habilitada la venta online
	 *
	 * @param bool $fecha_cad Indica si los artículos tienen fecha de caducidad
	 *
	 * @return void
	 */
	public function saveAppData(string $tipo_iva, array $iva_list, array $margin_list, bool $venta_online, bool $fecha_cad): void {
		$app_data_file = $this->getConfig()->getDir('app_cache').'app_data.json';

		$data = [
			'tipoIva'     => $tipo_iva,
			'ivaList'     => $iva_list,
			'marginList'  => $margin_list,
			'ventaOnline' => $venta_online,
			'fechaCad'    => $fecha_cad
		];

		$data_str = json_encode($data);

		file_put_contents($app_data_file, $data_str);
	}
}