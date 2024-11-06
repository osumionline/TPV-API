<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Task;

use Osumi\OsumiFramework\Core\OTask;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Utils\AppData;
use Osumi\OsumiFramework\App\Model\Cliente;
use Osumi\OsumiFramework\App\Model\Categoria;
use Osumi\OsumiFramework\App\Model\Marca;
use Osumi\OsumiFramework\App\Model\Proveedor;
use Osumi\OsumiFramework\App\Model\ProveedorMarca;
use Osumi\OsumiFramework\App\Model\Articulo;
use Osumi\OsumiFramework\App\Model\CodigoBarras;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\LineaVenta;
use Osumi\OsumiFramework\App\Model\TipoPago;
use Osumi\OsumiFramework\App\Model\Pedido;
use Osumi\OsumiFramework\App\Model\LineaPedido;
use Osumi\OsumiFramework\App\Service\ArticulosService;

// tabla modelos ref_orig variois

class ImportTask extends OTask {
	private ?ArticulosService $articulos_service = null;
	private ?AppData  $app_data  = null;
	private ?TipoPago $visa      = null;
	private ?TipoPago $web       = null;
	private ?int      $id_varios = null;
	private ?string   $log_route = null;

  function __construct() {
		$this->articulos_service = inject(ArticulosService::class);
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
		OTools::checkOfw('cache');
		$provinces_file = $this->getConfig()->getDir('ofw_cache') . 'provinces.json';
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
				if (OTools::slugify($check_item) === $province_slug) {
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
				$list[] = $data;
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
				$list[] = $data;
				$ind = count($list) -1;
			}
			else {
				if ($data[1] === 'Id') {
					continue;
				}
				$list[$ind]['lineas'][] = $data;
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
			$c = Cliente::create();
			$c->nombre_apellidos = $item[1];
			$c->dni_cif          = empty($item[2]) ? null : $item[2];
			$c->telefono         = empty($item[3]) ? null : $item[3];
			$c->email            = empty($item[4]) ? null : $item[4];
			$c->direccion        = empty($item[5]) ? null : $item[5];
			$c->codigo_postal    = empty($item[6]) ? null : $item[6];
			$c->poblacion        = empty($item[7]) ? null : $item[7];
			$c->provincia        = $this->findProvince($item[8]);
			$c->fact_igual       = true;
			$c->save();

			$this->clientes[intval($item[0])] = $c;

			$this->logMessage("  Nuevo cliente \"".$this->getColors()->getColoredString($c->nombre_apellidos, "light_green")."\" cargado.");
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
			$c = Categoria::create();
			$c->id_padre = null;
			$c->nombre   = empty($item[1]) ? null : $item[1];
			$c->save();

			$this->categorias[intval($item[0])] = $c;

			$this->logMessage("  Nueva categoría \"".$this->getColors()->getColoredString($c->nombre, "light_green")."\" cargada.");
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
			$m = Marca::create();
			$m->nombre = empty($item[1]) ? null : $item[1];
			$m->save();

			$this->marcas[intval($item[0])] = $m;

			$this->logMessage("  Nueva marca \"".$this->getColors()->getColoredString($m->nombre, "light_green")."\" cargada.");

			if (!empty($item[2])) {
				$p = Proveedor::findOne(['nombre' => $item[2]]);
				if (is_null($p)) {
					$p = Proveedor::create();
					$p->nombre = $item[2];
					$p->save();

					$this->logMessage("  Nuevo proveedor \"".$this->getColors()->getColoredString($p->nombre, "light_green")."\" cargado.");
				}
				$pm = ProveedorMarca::create();
				$pm->id_proveedor = $p->id;
				$pm->id_marca     = $m->id;
				$pm->save();
			}
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
			if (intval($item[0]) === $this->id_varios) {
				$this->logMessage("Artículo \"Varios\" encontrado.");
				continue;
			}
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

			$a = Articulo::create();
			$a->localizador         = empty($item[1]) ? intval($this->articulos_service->getNewLocalizador()) : intval($item[1]);
			$a->nombre              = empty($item[7]) ? null : $item[7];
			$a->slug                = empty($item[7]) ? null : OTools::slugify($item[7]);
			$a->id_categoria        = null;
			$a->id_marca            = $this->marcas[$item[2]]->id;
			$a->referencia          = empty($item[6]) ? null : $item[6];
			$a->palb                = $palb;
			$a->puc                 = $puc;
			$a->pvp                 = $pvp;
			$a->iva                 = $iva;
			$a->re                  = $re;
			$a->margen              = $margen;
			$a->stock               = intval($item[8]);
			$a->stock_min           = 0;
			$a->stock_max           = 0;
			$a->lote_optimo         = 0;
			$a->venta_online        = false;
			$a->fecha_caducidad     = null;
			$a->mostrar_en_web      = false;
			$a->desc_corta          = null;
			$a->descripcion         = null;
			$a->observaciones       = null;
			$a->mostrar_obs_pedidos = false;
			$a->mostrar_obs_ventas  = false;
			$a->acceso_directo      = null;
			$a->save();

			$cb = CodigoBarras::create();
			$cb->id_articulo   = $a->id;
			$cb->codigo_barras = $a->localizador;
			$cb->por_defecto   = true;
			$cb->save();

			if (!empty($item[12])) {
				if (is_numeric($item[12])) {
					$cb = CodigoBarras::create();
					$cb->id_articulo   = $a->id;
					$cb->codigo_barras = intval($item[12]);
					$cb->por_defecto   = false;
					$cb->save();
				}
			}

			$this->articulos[intval($item[0])] = $a;

			$this->logMessage("  Nuevo artículo \"".$this->getColors()->getColoredString($a->nombre, "light_green")."\" cargado.");
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

		foreach ($list as $item) {
			$error     = false;
			$total     = floatval(str_ireplace(',', '.', $item[2]));
			$entregado = floatval(str_ireplace(',', '.', $item[3]));
			$tarjeta   = floatval(str_ireplace(',', '.', $item[4]));
			$web       = floatval(str_ireplace(',', '.', $item[5]));

			$v = Venta::create();
			$v->num_venta   = intval($item[0]);
			$v->id_empleado = 1;
			$v->id_cliente  = empty($item[1]) ? null : $this->clientes[$item[1]]->id;
			$v->total       = $total;
			$v->entregado   = $entregado;
			$v->pago_mixto  = false;
			$id_tipo_pago = null;
			if ($tarjeta !== 0) {
				$id_tipo_pago = $this->visa->id;
			}
			if ($web !== 0) {
				$id_tipo_pago = $this->web->id;
			}
			$v->id_tipo_pago   = $id_tipo_pago;
			$v->entregado_otro = 0;
			$v->saldo          = null;
			$v->facturada      = false;
			$v->save();

			$v->created_at = $item[6];
			$v->save();

			foreach ($item['lineas'] as $linea) {
				if (!array_key_exists($linea[2], $this->articulos)) {
					$id_articulo = null;
					$iva = 21;
				}
				else {
					$id_articulo = $this->articulos[$linea[2]]->id;
					$iva         = $this->articulos[$linea[2]]->iva;
				}
				$lv = LineaVenta::create();
				$lv->id_venta          = $v->id;
				$lv->id_articulo       = $id_articulo;
				$lv->nombre_articulo   = $linea[3];
				$lv->puc               = floatval(str_ireplace(',', '.', $linea[4]));
				$lv->pvp               = floatval(str_ireplace(',', '.', $linea[5]));
				$lv->iva               = $iva;
				$lv->importe           = floatval(str_ireplace(',', '.', $linea[8]));
				$lv->descuento         = intval($linea[6]);
				$lv->importe_descuento = null;
				$lv->devuelto          = 0;
				$lv->unidades          = intval($linea[7]);
				$lv->save();
			}

			$this->logMessage("  Nueva venta \"".$this->getColors()->getColoredString($item[0], "light_green")."\" cargada.");
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
					$importe += $this->articulos[$linea[2]]->pvp * intval($linea[5]);
				}
				else {
					$error = true;
					break;
				}
			}

			if (!$error) {
				$p = Pedido::create();
				$p->id_proveedor       = null;
				$p->metodo_pago        = null;
				$p->tipo               = null;
				$p->num                = null;
				$p->importe            = $importe;
				$p->portes             = 0;
				$p->descuento          = 0;
				$p->fecha_pago         = $item[2];
				$p->fecha_pedido       = $item[2];
				$p->fecha_recepcionado = empty($item[3]) ? null : $item[3];
				$p->re                 = false;
				$p->europeo            = false;
				$p->faltas             = false;
				$p->recepcionado       = empty($item[3]) ? false : true;
				$p->observaciones      = null;
				$p->save();

				$p->created_at = $item[1];
				$p->save();

				foreach ($item['lineas'] as $linea) {
					$lp = LineaPedido::create();
					$lp->id_pedido       = $p->id;
					$lp->id_articulo     = $this->articulos[$linea[2]]->id;
					$lp->nombre_articulo = $this->articulos[$linea[2]]->nombre;
					$lp->codigo_barras   = empty($linea[4]) ? null : intval($linea[4]);
					$lp->unidades        = empty($linea[5]) ? 0 : intval($linea[5]);
					$lp->palb            = $this->articulos[$linea[2]]->palb;
					$lp->pvp             = $this->articulos[$linea[2]]->pvp;
					$lp->margen          = $this->articulos[$linea[2]]->margen;
					$lp->iva             = $this->articulos[$linea[2]]->iva;
					$lp->re              = $this->articulos[$linea[2]]->re;
					$lp->descuento       = 0;
					$lp->save();
				}

				$this->logMessage("  Nuevo pedido \"".$this->getColors()->getColoredString($item[0], "light_green")."\" cargado.");
			}
			else {
				$error_list[] = $item[0];
				$this->logMessage("  ".$this->getColors()->getColoredString("ERROR", "red")." Al guardar el pedido \"".$this->getColors()->getColoredString($item[0], "light_green")."\" no se ha encontrado un artículo.");
			}
		}

		if (count($error_list) > 0) {
			$this->logMessage("  ".$this->getColors()->getColoredString("ERROR", "red")." Los siguientes pedidos han dado un error: ".$this->getColors()->getColoredString(implode(', ', $error_list), 'red'));
		}
		$this->logMessage("Pedidos cargados.\n");
	}

	public function run(array $options = []): void {
		OTools::checkOfw('cache');
		OTools::checkOfw('logs');
		OTools::checkOfw('tmp');

		if (count($options) < 1) {
			echo "\nERROR: Tienes que indicar el id del artículo \"Varios\".\n\n";
			echo "  ofw import --varios 178\n\n";
			exit();
		}

		$this->id_varios = intval($options['varios']);

		// Cargo archivo de configuración
		$app_data_file = $this->getConfig()->getDir('ofw_cache') . 'app_data.json';
		$app_data = new AppData($app_data_file);
		if (!$app_data->getLoaded()) {
			echo "ERROR: No se encuentra el archivo de configuración del sitio o está mal formado.\n";
			exit();
		}
		$this->app_data = $app_data;

		// Archivo de logs
		$this->log_route = $this->getConfig()->getDir('logs') . 'import_' . date('Y-m-d', time()) . '.log';
		if (file_exists($this->log_route)) {
			unlink($this->log_route);
			$this->logMessage("El archivo de logs ya existía, ha sido borrado.");
		}

		// Cargo lista de provincias
		$this->loadProvinces();

		// Busco tipo de pago VISA
		$tp = TipoPago::findOne(['slug' => 'visa']);
		$this->visa = $tp;

		// Busco tipo de pago WEB
		$tp = TipoPago::findOne(['slug' => 'web']);
		$this->web = $tp;

		// Borro todos los datos
		$this->logMessage("Borrando base de datos actual...");
		$this->resetApp();

		// Cargo lista de clientes
		$clientes_file = $this->getConfig()->getDir('ofw_tmp') . 'clientes.csv';
		if (file_exists($clientes_file)) {
			$this->logMessage("Cargando clientes...");
			$this->loadClientes($clientes_file);
		}

		// Cargo lista de categorías
		$categorias_file = $this->getConfig()->getDir('ofw_tmp') . 'categorias.csv';
		if (file_exists($categorias_file)) {
			$this->logMessage("Cargando categorías...");
			$this->loadCategorias($categorias_file);
		}

		// Cargo lista de marcas
		$marcas_file = $this->getConfig()->getDir('ofw_tmp') . 'marcas.csv';
		if (file_exists($marcas_file)) {
			$this->logMessage("Cargando marcas...");
			$this->loadMarcas($marcas_file);
		}

		// Cargo lista de artículos
		$articulos_file = $this->getConfig()->getDir('ofw_tmp') . 'articulos.csv';
		if (file_exists($articulos_file)) {
			$this->logMessage("Cargando artículos...");
			$this->loadArticulos($articulos_file);
		}

		// Cargo lista de ventas
		$ventas_file = $this->getConfig()->getDir('ofw_tmp') . 'ventas.csv';
		if (file_exists($ventas_file)) {
			$this->logMessage("Cargando ventas...");
			$this->loadVentas($ventas_file);
		}

		// Cargo lista de pedidos
		$pedidos_file = $this->getConfig()->getDir('ofw_tmp') . 'pedidos.csv';
		if (file_exists($pedidos_file)) {
			$this->logMessage("Cargando pedidos...");
			$this->loadPedidos($pedidos_file);
		}
	}
}
