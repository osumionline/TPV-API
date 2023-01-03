<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\OFW\DB\ODB;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Model\Categoria;
use OsumiFramework\App\Model\Marca;

class importTask extends OTask {
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
			$data = str_getcsv($line);
			if ($data[0] !== 'Id') {
				array_push($list, $data);
			}
		}
		return $list;
	}

	/**
	 * Función para cargar una lista de clientes a partir de un archivo csv.
	 * La función primero borra todos los datos de clientes existentes actualmente.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadClientes(string $file): void {
		$db = new ODB();
		$sql = "SELECT * FROM `cliente`";
		$db->query($sql);

		while ($res = $db->next()) {
			$c = new Cliente();
			$c->update($res);
			echo "  Borro cliente \"".$this->getColors()->getColoredString($c->get('nombre_apellidos'), "light_green")."\"\n";
			$c->deleteFull();
		}

		$sql = "ALTER TABLE `cliente` AUTO_INCREMENT = 1";
		$db->query($sql);

		$list = $this->readCSVFile($file);
		echo "\n";

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

			echo "  Nuevo cliente \"".$this->getColors()->getColoredString($c->get('nombre_apellidos'), "light_green")."\" cargado.\n";
		}

		echo "Clientes cargados.\n\n";
	}

	/**
	 * Función para cargar una lista de categorías a partir de un archivo csv.
	 * La función primero borra todos los datos de las categorías existentes actualmente.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadCategorias(string $file): void {
		$db = new ODB();
		$sql = "SELECT * FROM `categoria`";
		$db->query($sql);

		while ($res = $db->next()) {
			$c = new Categoria();
			$c->update($res);
			echo "  Borro categoría \"".$this->getColors()->getColoredString($c->get('nombre'), "light_green")."\"\n";
			$c->deleteFull();
		}

		$sql = "ALTER TABLE `categoria` AUTO_INCREMENT = 1";
		$db->query($sql);

		$list = $this->readCSVFile($file);
		echo "\n";

		foreach ($list as $item) {
			$c = new Categoria();
			$c->set('id_padre', null);
			$c->set('nombre',   empty($item[1]) ? null : $item[1]);
			$c->save();

			$this->categorias[intval($item[0])] = $c;

			echo "  Nueva categoría \"".$this->getColors()->getColoredString($c->get('nombre'), "light_green")."\" cargada.\n";
		}

		echo "Categorías cargadas.\n\n";
	}

	/**
	 * Función para cargar una lista de marcas a partir de un archivo csv.
	 * La función primero borra todos los datos de las marcas existentes actualmente.
	 *
	 * @param string $file Ruta al archivo CSV a cargar
	 *
	 * @return void
	 */
	private function loadMarcas(string $file): void {
		$db = new ODB();
		$sql = "SELECT * FROM `marca`";
		$db->query($sql);

		while ($res = $db->next()) {
			$m = new Marca();
			$m->update($res);
			echo "  Borro marca \"".$this->getColors()->getColoredString($m->get('nombre'), "light_green")."\"\n";
			$m->deleteFull();
		}

		$sql = "ALTER TABLE `marca` AUTO_INCREMENT = 1";
		$db->query($sql);

		$list = $this->readCSVFile($file);
		echo "\n";

		foreach ($list as $item) {
			$m = new Marca();
			$m->set('nombre', empty($item[1]) ? null : $item[1]);
			$m->save();

			$this->marcas[intval($item[0])] = $m;

			echo "  Nueva marca \"".$this->getColors()->getColoredString($m->get('nombre'), "light_green")."\" cargada.\n";
		}

		echo "Marcas cargadas.\n\n";
	}

	public function run(array $options=[]): void {
		// Cargo lista de provincias
		$this->loadProvinces();

		// Cargo lista de clientes
		$clientes_file = $this->getConfig()->getDir('ofw_tmp').'clientes.csv';
		if (file_exists($clientes_file)) {
			echo "Cargando clientes...\n";
			$this->loadClientes($clientes_file);
		}

		// Cargo lista de categorías
		$categorias_file = $this->getConfig()->getDir('ofw_tmp').'categorias.csv';
		if (file_exists($categorias_file)) {
			echo "Cargando categorías...\n";
			$this->loadCategorias($categorias_file);
		}

		// Cargo lista de marcas
		$marcas_file = $this->getConfig()->getDir('ofw_tmp').'marcas.csv';
		if (file_exists($marcas_file)) {
			echo "Cargando marcas...\n";
			$this->loadMarcas($marcas_file);
		}
	}
}
