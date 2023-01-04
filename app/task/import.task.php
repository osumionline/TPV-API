<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Utils\AppData;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Model\Categoria;
use OsumiFramework\App\Model\Marca;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\LineaVenta;
use OsumiFramework\App\Model\TipoPago;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Model\LineaPedido;
use OsumiFramework\App\Service\articulosService;

class importTask extends OTask {
	private ?articulosService $articulos_service = null;
	private ?AppData $app_data = null;
	private ?TipoPago $visa = null;
	private ?TipoPago $web = null;
	private ?string $log_route = null;

  function __construct() {
		$this->articulos_service = new articulosService();
  }

	public function __toString() {
		return "import: Tarea para importar datos de Berein TPV";
	}

	/**
	 * Lista global de provincias
	 */
	private $provinces = [];
	/**
	 * Lista global de clientes, para ser utilizada al cargar otros archivos
	 */
	private array $clientes = [];
	/**
	 * Lista global de categorías, para ser utilizada al cargar otros archivos
	 */
	private array $categorias = [];
	/**
	 * Lista global de marcas, para ser utilizada al cargar otros archivos
	 */
	private array $marcas = [];
	/**
	 * Lista global de artículos, para ser utilizada al cargar otros archivos
	 */
	private array $articulos = [];

	private function logMessage($str): void {
		$str .= "\n";
		file_put_contents($this->log_route, $str, FILE_APPEND);
		echo $str;
	}

	/**
	 * Función que lee el archivo de provincias y lo carga en memoria
	 */
	private function loadProvinces(): void {
		$provinces_file = $this->getConfig()->getDir('ofw_cache').'provinces.json';
		$data = json_decode(file_get_contents($provinces_file), true);
		foreach ($data['ccaa'] as $ccaa) {
			foreach ($ccaa['provinces'] as $province) {
				$this->provinces[$province['id']] = $province['name'];
			}
		}
	}

	/**
	 * Función para borrar todos los datos de la aplicación
	 *
	 * @return void
	 */
	private function resetApp(): void {
		$db = new ODB();

		$sql = "SET FOREIGN_KEY_CHECKS = 0";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `caja_tipo`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `caja`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `pago_caja`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `linea_pedido`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `pdf_pedido`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `vista_pedido`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `pedido`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `linea_venta`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `factura_venta`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `factura`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `venta`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `cliente`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `proveedor_marca`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `comercial`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `articulo_etiqueta`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `articulo_etiqueta_web`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `etiqueta`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `etiqueta_web`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `articulo_foto`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `foto`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `codigo_barras`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `articulo`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `categoria`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `marca`";
		$db->query($sql);
		$sql = "TRUNCATE TABLE `proveedor`";
		$db->query($sql);
		$sql = "SET FOREIGN_KEY_CHECKS = 1";
		$db->query($sql);
	}

	/**
	 * Función para buscar una provincia en la lista de provincias, pasa todo a minúsculas y comprueba casos de varios nombres
	 *
	 * @param string $province Provincia a buscar
	 *
	 * @return int Id de la provincia encontrada o null si no se encuentra
	 */
	private function findProvince(string $province): ?int {
		$province_slug = OTools::slugify($province);

		foreach ($this->provinces as $ind => $check) {
			$data = explode('-', strtolower($check));
			foreach ($data as $check_item) {
				if (OTools::slugify($check_item) == $province_slug) {
					return $ind;
				}
			}
		}
		return null;
	}

	/**
	 * Función para leer un archivo CSV
	 *
	 * @param string Ruta al archivo CSV que se tiene que leer
	 *
	 * @return array Array con las líneas del archivo CSV
	 */
	private function readCSVFile(string $path): array {
		$list = [];
		$file = file($path, FILE_SKIP_EMPTY_LINES);
		foreach ($file as $line) {
			$data = str_getcsv($line, ";");
			if ($data[0] !== 'Id') {
				array_push($list, $data);
			}
		}
		return $list;
	}

	/**
	 * Función para leer un archivo CSV con "sub-líneas"
	 *
	 * @param string Ruta al archivo CSV que se tiene que leer
	 *
	 * @return array Array con las líneas del archivo CSV
	 */
	private function readCSVFileWithSubLines(string $path): array {
		$list = [];
		$file = file($path, FILE_SKIP_EMPTY_LINES);

		$ind = 0;
		for ($i = 0; $i < count($file); $i++) {
			$line = $file[$i];
			$data = str_getcsv($line, ";");
			if ($data[0] === 'Id') {
				$ind++;
				continue;
			}
			if ($data[0] !== ' ') {
				$data['lineas'] = [];
				array_push($list, $data);
				$ind = count($list) -1;
			}
			else {
				if ($data[1] === 'Id') {
					continue;
				}
				array_push($list[$ind]['lineas'], $data);
			}
		}

		return $list;
	}

	/**
	 * Función para cargar una lista de clientes a partir de un archivo csv.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadClientes(string $file): void {
		$list = $this->readCSVFile($file);

		foreach ($list as $item) {
			$c = new Cliente();
			$c->set('nombre_apellidos', $item[1]);
			$c->set('dni_cif',          empty($item[2]) ? null : $item[2]);
			$c->set('telefono',         empty($item[3]) ? null : $item[3]);
			$c->set('email',            empty($item[4]) ? null : $item[4]);
			$c->set('direccion',        empty($item[5]) ? null : $item[5]);
			$c->set('codigo_postal',    empty($item[6]) ? null : $item[6]);
			$c->set('poblacion',        empty($item[7]) ? null : $item[7]);
			$c->set('provincia',        $this->findProvince($item[8]));
			$c->set('fact_igual',       true);
			$c->save();

			$this->clientes[intval($item[0])] = $c;

			$this->logMessage("  Nuevo cliente \"".$this->getColors()->getColoredString($c->get('nombre_apellidos'), "light_green")."\" cargado.");
		}

		$this->logMessage("Clientes cargados.\n");
	}

	/**
	 * Función para cargar una lista de categorías a partir de un archivo csv.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadCategorias(string $file): void {
		$list = $this->readCSVFile($file);

		foreach ($list as $item) {
			$c = new Categoria();
			$c->set('id_padre', null);
			$c->set('nombre',   empty($item[1]) ? null : $item[1]);
			$c->save();

			$this->categorias[intval($item[0])] = $c;

			$this->logMessage("  Nueva categoría \"".$this->getColors()->getColoredString($c->get('nombre'), "light_green")."\" cargada.");
		}

		$this->logMessage("Categorías cargadas.\n");
	}

	/**
	 * Función para cargar una lista de marcas a partir de un archivo csv.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadMarcas(string $file): void {
		$list = $this->readCSVFile($file);

		foreach ($list as $item) {
			$m = new Marca();
			$m->set('nombre', empty($item[1]) ? null : $item[1]);
			$m->save();

			$this->marcas[intval($item[0])] = $m;

			$this->logMessage("  Nueva marca \"".$this->getColors()->getColoredString($m->get('nombre'), "light_green")."\" cargada.");
		}

		$this->logMessage("Marcas cargadas.\n");
	}

	/**
	 * Función para cargar una lista de artículos a partir de un archivo csv.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadArticulos(string $file): void {
		$list = $this->readCSVFile($file);

		foreach ($list as $item) {
			$puc = floatval(str_ireplace(',', '.', $item[9]));
			$iva = intval($item[10]);
			if ($iva === 0) {
				$iva = 21;
			}
			$pvp = floatval(str_ireplace(',', '.', $item[11]));
			$palb = $puc / ((100 + $iva) / 100);
			$palb = floatval(number_format($palb, 2));
			$re = 0;
			if ($this->app_data->getTipoIva() === 're') {
				if ($iva === 4) {
					$re = 0.5;
				}
				if ($iva === 10) {
					$re = 1.4;
				}
				if ($iva === 21) {
					$re = 5.2;
				}
			}
			if ($pvp == 0) {
				$margen = 0;
			}
			else {
				$margen = (100 * ($pvp - $puc)) / $pvp;
			}

			$a = new Articulo();
			$a->set('localizador', empty($item[1]) ? intval($this->articulos_service->getNewLocalizador()) : intval($item[1]));
			$a->set('nombre', empty($item[7]) ? null : $item[7]);
			$a->set('slug', empty($item[7]) ? null : OTools::slugify($item[7]));
			$a->set('id_categoria', $this->categorias[$item[4]]->get('id'));
			$a->set('id_marca', $this->marcas[$item[2]]->get('id'));
			$a->set('referencia', empty($item[6]) ? null : $item[6]);
			$a->set('palb', $palb);
			$a->set('puc', $puc);
			$a->set('pvp', $pvp);
			$a->set('iva', $iva);
			$a->set('re', $re);
			$a->set('margen', $margen);
			$a->set('stock', intval($item[8]));
			$a->set('stock_min', 0);
			$a->set('stock_max', 0);
			$a->set('lote_optimo', 0);
			$a->set('venta_online', false);
			$a->set('fecha_caducidad', null);
			$a->set('mostrar_en_web', false);
			$a->set('desc_corta', null);
			$a->set('descripcion', null);
			$a->set('observaciones', null);
			$a->set('mostrar_obs_pedidos', false);
			$a->set('mostrar_obs_ventas', false);
			$a->set('acceso_directo', null);
			$a->save();

			$cb = new CodigoBarras();
			$cb->set('id_articulo', $a->get('id'));
			$cb->set('codigo_barras', $a->get('localizador'));
			$cb->set('por_defecto', true);
			$cb->save();

			if (!empty($item[12])) {
				if (is_numeric($item[12])) {
					$cb = new CodigoBarras();
					$cb->set('id_articulo', $a->get('id'));
					$cb->set('codigo_barras', intval($item[12]));
					$cb->set('por_defecto', false);
					$cb->save();
				}
			}

			$this->articulos[intval($item[0])] = $a;

			$this->logMessage("  Nuevo artículo \"".$this->getColors()->getColoredString($a->get('nombre'), "light_green")."\" cargado.");
		}

		$this->logMessage("Artículos cargados.\n");
	}

	/**
	 * Función para cargar una lista de ventas a partir de un archivo csv.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadVentas(string $file): void {
		$list = $this->readCSVFileWithSubLines($file);
		$error_list = [];

		foreach ($list as $item) {
			$error = false;
			$total = floatval($item[2]);
			$entregado = floatval($item[3]);
			$tarjeta = floatval($item[4]);
			$web = floatval($item[5]);

			$v = new Venta();
			$v->set('id_empleado', 1);
			$v->set('id_cliente', empty($item[1]) ? null : $this->clientes[$item[1]]->get('id'));
			$v->set('total', $total);
			$v->set('entregado', $entregado);
			$v->set('pago_mixto', false);
			$id_tipo_pago = null;
			if ($tarjeta > 0) {
				$id_tipo_pago = $this->visa->get('id');
			}
			if ($web > 0) {
				$id_tipo_pago = $this->web->get('id');
			}
			$v->set('id_tipo_pago', $id_tipo_pago);
			$v->set('entregado_otro', 0);
			$v->set('saldo', null);
			$v->save();

			$v->set('created_at', $item[6]);
			$v->save();

			foreach ($item['lineas'] as $linea) {
				if (!array_key_exists($linea[2], $this->articulos)) {
					$error = true;
					break;
				}
				$lv = new LineaVenta();
				$lv->set('id_venta', $v->get('id'));
				$lv->set('id_articulo', $this->articulos[$linea[2]]->get('id'));
				$lv->set('nombre_articulo', $linea[3]);
				$lv->set('puc', $this->articulos[$linea[2]]->get('puc'));
				$lv->set('pvp', $this->articulos[$linea[2]]->get('pvp'));
				$lv->set('iva', $this->articulos[$linea[2]]->get('iva'));
				$lv->set('re', $this->articulos[$linea[2]]->get('re'));
				$lv->set('importe', floatval($linea[8]));
				$lv->set('descuento', intval($linea[6]));
				$lv->set('importe_descuento', null);
				$lv->set('devuelto', 0);
				$lv->set('unidades', intval($linea[7]));
				$lv->save();
			}

			if (!$error) {
				$this->logMessage("  Nueva venta \"".$this->getColors()->getColoredString($item[0], "light_green")."\" cargada.");
			}
			else {
				$v->deleteFull();
				array_push($error_list, $item[0]);
				$this->logMessage("  ".$this->getColors()->getColoredString("ERROR", "red")." Al guardar la venta \"".$this->getColors()->getColoredString($item[0], "light_green")."\" no se ha encontrado un artículo.");
			}
		}

		if (count($error_list) > 0) {
			$this->logMessage("  ".$this->getColors()->getColoredString("ERROR", "red")." Las siguientes ventas han dado un error: ".$this->getColors()->getColoredString(implode(', ', $error_list), 'red'));
		}
		$this->logMessage("Ventas cargadas.\n");
	}

	/**
	 * Función para cargar una lista de pedidos a partir de un archivo csv.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadPedidos(string $file): void {
		$list = $this->readCSVFileWithSubLines($file);
		$error_list = [];

		foreach ($list as $item) {
			$importe = 0;
			$error = false;
			foreach ($item['lineas'] as $linea) {
				if (array_key_exists($linea[2], $this->articulos)) {
					$importe += $this->articulos[$linea[2]]->get('pvp') * intval($linea[5]);
				}
				else {
					$error = true;
					break;
				}
			}

			if (!$error) {
				$p = new Pedido();
				$p->set('id_proveedor', null);
				$p->set('metodo_pago', null);
				$p->set('tipo', null);
				$p->set('num', null);
				$p->set('importe', $importe);
				$p->set('portes', 0);
				$p->set('descuento', 0);
				$p->set('fecha_pago', $item[2]);
				$p->set('fecha_pedido', $item[2]);
				$p->set('fecha_recepcionado', empty($item[3]) ? null : $item[3]);
				$p->set('re', false);
				$p->set('europeo', false);
				$p->set('faltas', false);
				$p->set('recepcionado', empty($item[3]) ? false : true);
				$p->set('observaciones', null);
				$p->save();

				$p->set('created_at', $item[1]);
				$p->save();

				foreach ($item['lineas'] as $linea) {
					$lp = new LineaPedido();
					$lp->set('id_pedido', $p->get('id'));
					$lp->set('id_articulo', $this->articulos[$linea[2]]->get('id'));
					$lp->set('nombre_articulo', $this->articulos[$linea[2]]->get('nombre'));
					$lp->set('codigo_barras', empty($linea[4]) ? null : intval($linea[4]));
					$lp->set('unidades', empty($linea[5]) ? 0 : intval($linea[5]));
					$lp->set('palb', $this->articulos[$linea[2]]->get('palb'));
					$lp->set('pvp', $this->articulos[$linea[2]]->get('pvp'));
					$lp->set('margen', $this->articulos[$linea[2]]->get('margen'));
					$lp->set('iva', $this->articulos[$linea[2]]->get('iva'));
					$lp->set('re', $this->articulos[$linea[2]]->get('re'));
					$lp->set('descuento', 0);
					$lp->save();
				}

				$this->logMessage("  Nuevo pedido \"".$this->getColors()->getColoredString($item[0], "light_green")."\" cargado.");
			}
			else {
				array_push($error_list, $item[0]);
				$this->logMessage("  ".$this->getColors()->getColoredString("ERROR", "red")." Al guardar el pedido \"".$this->getColors()->getColoredString($item[0], "light_green")."\" no se ha encontrado un artículo.");
			}
		}

		if (count($error_list) > 0) {
			$this->logMessage("  ".$this->getColors()->getColoredString("ERROR", "red")." Los siguientes pedidos han dado un error: ".$this->getColors()->getColoredString(implode(', ', $error_list), 'red'));
		}
		$this->logMessage("Pedidos cargados.\n");
	}

	public function run(array $options=[]): void {
		require_once $this->getConfig()->getDir('app_utils').'AppData.php';

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache').'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}
		$this->app_data = $app_data;

		// Archivo de logs
		$this->log_route = $this->getConfig()->getDir('logs').'import.log';
		if (file_exists($this->log_route)) {
			unlink($this->log_route);
			$this->logMessage("El archivo de logs ya existía, ha sido borrado.");
		}

		// Cargo lista de provincias
		$this->loadProvinces();

		// Busco tipo de pago VISA
		$tp = new TipoPago();
		$tp->find(['slug' => 'visa']);
		$this->visa = $tp;

		// Busco tipo de pago WEB
		$tp = new TipoPago();
		$tp->find(['slug' => 'web']);
		$this->web = $tp;

		// Borro todos los datos
		$this->logMessage("Borrando base de datos actual...");
		$this->resetApp();

		// Cargo lista de clientes
		$clientes_file = $this->getConfig()->getDir('ofw_tmp').'clientes.csv';
		if (file_exists($clientes_file)) {
			$this->logMessage("Cargando clientes...");
			$this->loadClientes($clientes_file);
		}

		// Cargo lista de categorías
		$categorias_file = $this->getConfig()->getDir('ofw_tmp').'categorias.csv';
		if (file_exists($categorias_file)) {
			$this->logMessage("Cargando categorías...");
			$this->loadCategorias($categorias_file);
		}

		// Cargo lista de marcas
		$marcas_file = $this->getConfig()->getDir('ofw_tmp').'marcas.csv';
		if (file_exists($marcas_file)) {
			$this->logMessage("Cargando marcas...");
			$this->loadMarcas($marcas_file);
		}

		// Cargo lista de artículos
		$articulos_file = $this->getConfig()->getDir('ofw_tmp').'articulos.csv';
		if (file_exists($articulos_file)) {
			$this->logMessage("Cargando artículos...");
			$this->loadArticulos($articulos_file);
		}

		// Cargo lista de ventas
		$ventas_file = $this->getConfig()->getDir('ofw_tmp').'ventas.csv';
		if (file_exists($ventas_file)) {
			$this->logMessage("Cargando ventas...");
			$this->loadVentas($ventas_file);
		}

		// Cargo lista de pedidos
		$pedidos_file = $this->getConfig()->getDir('ofw_tmp').'pedidos.csv';
		if (file_exists($pedidos_file)) {
			$this->logMessage("Cargando pedidos...");
			$this->loadPedidos($pedidos_file);
		}
	}
}
