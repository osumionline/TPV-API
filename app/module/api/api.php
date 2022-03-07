<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\App\Model\Articulo;
use OsumiFramework\App\Model\Proveedor;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\Caja;
use OsumiFramework\App\Model\Marca;
use OsumiFramework\App\Model\Cliente;
use OsumiFramework\App\Service\generalService;
use OsumiFramework\App\Service\articulosService;
use OsumiFramework\App\Service\clientesService;
use OsumiFramework\App\DTO\InstallationDTO;
use OsumiFramework\App\DTO\MarcaDTO;
use OsumiFramework\App\DTO\ProveedorDTO;
use OsumiFramework\App\DTO\ArticuloDTO;
use OsumiFramework\App\DTO\ClienteDTO;

#[ORoute(
	type: 'json',
	prefix: '/api'
)]
class api extends OModule {
	private ?generalService   $general_service   = null;
	private ?articulosService $articulos_service = null;
	private ?clientesService  $clientes_service  = null;

	function __construct() {
		$this->general_service   = new generalService();
		$this->articulos_service = new articulosService();
		$this->clientes_service  = new clientesService();
	}

	/**
	 * Función para obtener los datos iniciales de configuración y comprobar el cierre de caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/checkStart')]
	public function checkStart(ORequest $req): void {
		$status   = 'ok';
		$date     = $req->getParamString('date');
		$opened   = 'false';
		$app_data = 'null';
		$tipos_pago = [];

		if (is_null($date)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$opened     = $this->general_service->getOpened($date) ? 'true' : 'false';
			$app_data   = $this->general_service->getAppData();
			$tipos_pago = $this->general_service->getTiposPago();
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('opened',  $opened);
		$this->getTemplate()->add('appData', $app_data, 'nourlencode');
		$this->getTemplate()->addComponent('tiposPago', 'model/tipo_pago_list', ['list' => $tipos_pago, 'extra' => 'nourlencode']);
	}

	/**
	 * Función guardar los datos iniciales de configuración
	 *
	 * @param InstallationDTO $data Objeto con la información sobre la instalación
	 * @return void
	 */
	#[ORoute('/saveInstallation')]
	public function saveInstallation(InstallationDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$this->general_service->saveAppData($data);
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para abrir la caja
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/openBox')]
	public function openBox(ORequest $req): void {
		$status = 'ok';

		$caja = new Caja();
		$caja->set('apertura',         date('Y-m-d H:i:s', time()));
		$caja->set('cierre',           null);
		$caja->set('ventas',           null);
		$caja->set('beneficios',       null);
		$caja->set('venta_efectivo',   null);
		$caja->set('venta_otros',      null);
		$caja->set('importe_apertura', null);
		$caja->set('importe_cierre',   null);

		$caja->save();

		$previous_id = $caja->get('id') -1;
		$previous_caja = new Caja();
		if ($previous_caja->find(['id'=>$previous_id])) {
			// La anterior caja se cierra en el momento en que la nueva se abre
			$previous_caja->set('cierre', $caja->get('apertura', 'Y-m-d H:i:s'));

			// Al cerrar la anterior caja actualizamos los valores comprobando las ventas
			$datos = $this->general_service->getVentasDia($previous_caja);

			$previous_caja->set('ventas', $datos['ventas']);
			$previous_caja->set('beneficios', $datos['beneficios'] - $this->general_service->getPagosCajaDia($previous_caja));
			$previous_caja->set('venta_efectivo', $datos['venta_efectivo']);
			$previous_caja->set('venta_otros', $datos['venta_otros']);
			$previous_caja->set('importe_cierre', $previous_caja->get('importe_apertura') + $datos['venta_efectivo']);

			$previous_caja->save();

			// Al abrir una caja nueva el importe que debería haber en caja es el que había al cerrar la anterior
			$caja->set('importe_apertura', $previous_caja->get('importe_cierre'));
			$caja->save();
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para obtener la lista de marcas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/getMarcas')]
	public function getMarcas(ORequest $req): void {
		$list = $this->articulos_service->getMarcas();

		$this->getTemplate()->addComponent('list', 'model/marca_list', ['list' => $list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para obtener la lista de proveedores
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/getProveedores')]
	public function getProveedores(ORequest $req): void {
		$list = $this->articulos_service->getProveedores();

		$this->getTemplate()->addComponent('list', 'model/proveedor_list', ['list' => $list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para obtener las estadísticas de ventas o web
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/getStatistics')]
	public function getStatistics(ORequest $req): void {

	}

	/**
	 * Función para obtener la lista de categorías
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/getCategorias')]
	public function getCategorias(ORequest $req): void {
		$list = $this->articulos_service->getCategoryTree([]);

		$this->getTemplate()->addComponent('list', 'api/categorias_list', ['list' => $list, 'extra'=>'nourlencode']);
	}

	/**
	 * Función para dar de baja un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/disableProduct')]
	public function disableProduct(ORequest $req): void {
		$status = 'ok';
		$id     = $req->getParamInt('id');

		if (is_null($id)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if ($art->find(['id' => $id])) {
				$art->set('deleted_at', date('Y-m-d H:i:s', time()));
				$art->save();
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/**
	 * Función para guardar una marca
	 *
	 * @param MarcaDTO $data Objeto con toda la información sobre una marca
	 * @return void
	 */
	#[ORoute('/saveMarca')]
	public function saveMarca(MarcaDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$marca = new Marca();
			if (!is_null($data->getId())) {
				$marca->find(['id' => $data->getId()]);
			}

			$marca->set('nombre',        urldecode($data->getNombre()));
			$marca->set('direccion',     urldecode($data->getDireccion()));
			$marca->set('telefono',      urldecode($data->getTelefono()));
			$marca->set('email',         urldecode($data->getEmail()));
			$marca->set('web',           urldecode($data->getWeb()));
			$marca->set('observaciones', urldecode($data->getObservaciones()));

			$marca->save();

			$data->setId( $marca->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}

	/**
	 * Función para guardar un proveedor
	 *
	 * @param ProveedorDTO $data Objeto con toda la información sobre un proveedor
	 * @return void
	 */
	#[ORoute('/saveProveedor')]
	public function saveProveedor(ProveedorDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$proveedor = new Proveedor();
			if (!is_null($data->getId())) {
				$proveedor->find(['id' => $data->getId()]);
			}

			$proveedor->set('nombre',        urldecode($data->getNombre()));
			$proveedor->set('direccion',     urldecode($data->getDireccion()));
			$proveedor->set('telefono',      urldecode($data->getTelefono()));
			$proveedor->set('email',         urldecode($data->getEmail()));
			$proveedor->set('web',           urldecode($data->getWeb()));
			$proveedor->set('observaciones', urldecode($data->getObservaciones()));

			$proveedor->save();

			$this->articulos_service->updateProveedoresMarcas($proveedor->get('id'), $data->getMarcas());

			$data->setId( $proveedor->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}

	/**
	 * Función para guardar un artículo
	 *
	 * @param ArticuloDTO $data Objeto con toda la información sobre un artículo
	 * @return void
	 */
	#[ORoute('/saveArticulo')]
	public function saveArticulo(ArticuloDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if (!is_null($data->getId())) {
				$art->find(['id' => $data->getId()]);
			}
			else {
				$data->setLocalizador( intval($this->articulos_service->getNewLocalizador()) );
				$this->getLog()->debug(var_export($data, true));
			}
			$fecha_caducidad = null;
			if (!is_null($data->getFechaCaducidad())) {
				$fec_cad_data = explode('-', $data->getFechaCaducidad());
				$time = mktime(0, 0, 0, intval($fec_cad_data[0]), 1, (2000 + intval($fec_cad_data[1])));
				$fecha_caducidad = date('Y-m-d H:i:s', $time);
			}
			$art->set('localizador',         $data->getLocalizador());
			$art->set('nombre',              urldecode($data->getNombre()));
			$art->set('slug',                OTools::slugify(urldecode($data->getNombre())));
			$art->set('id_categoria',        $data->getIdCategoria());
			$art->set('id_marca',            $data->getIdMarca());
			$art->set('id_proveedor',        $data->getIdProveedor());
			$art->set('referencia',          $data->getReferencia());
			$art->set('palb',                $data->getPalb());
			$art->set('puc',                 $data->getPuc());
			$art->set('pvp',                 $data->getPvp());
			$art->set('iva',                 $data->getIva());
			$art->set('re',                  $data->getRe());
			$art->set('margen',              $data->getMargen());
			$art->set('stock',               $data->getStock());
			$art->set('stock_min',           $data->getStockMin());
			$art->set('stock_max',           $data->getStockMax());
			$art->set('lote_optimo',         $data->getLoteOptimo());
			$art->set('venta_online',        $data->getVentaOnline());
			$art->set('fecha_caducidad',     $fecha_caducidad);
			$art->set('mostrar_en_web',      $data->getMostrarEnWeb());
			$art->set('desc_corta',          urldecode($data->getDescCorta()));
			$art->set('descripcion',         urldecode($data->getDescripcion()));
			$art->set('observaciones',       urldecode($data->getObservaciones()));
			$art->set('mostrar_obs_pedidos', $data->getMostrarObsPedidos());
			$art->set('mostrar_obs_ventas',  $data->getMostrarObsVentas());

			$art->save();
			$data->setId( $art->get('id') );

			$cod_barras_por_defecto = false;
			foreach ($data->getCodigosBarras() as $cod) {
				if (!empty($cod['codigoBarras'])) {
					$cb = new CodigoBarras();
					if (!empty($cod['id'])) {
						$cb->find(['id'=>$cod['id']]);
					}
					$cb->set('id_articulo', $data->getId());
					$cb->set('codigo_barras', $cod['codigoBarras']);
					if ($cb->get('por_defecto')) {
						$cod_barras_por_defecto = true;
					}
					$cb->save();
				}
			}

			if (!$cod_barras_por_defecto) {
				$cb = new CodigoBarras();
				$cb->set('id_articulo', $data->getId());
				$cb->set('codigo_barras', $data->getLocalizador());
				$cb->set('por_defecto', true);
				$cb->save();
			}

			$this->articulos_service->updateFotos($art, $data->getFotosList());
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('localizador', $data->getLocalizador());
	}

	/**
	 * Función para obtener los datos de un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/loadArticulo')]
	public function loadArticulo(ORequest $req): void {
		$status = 'ok';
		$localizador = $req->getParamInt('localizador');
		$articulo    = null;

		if (is_null($localizador)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cb = new CodigoBarras();
			if ($cb->find(['codigo_barras'=>$localizador])) {
				$articulo = new Articulo();
				$articulo->find(['id' => $cb->get('id_articulo')]);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('articulo', 'model/articulo', ['articulo' => $articulo, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para buscar artículos
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/searchArticulos')]
	public function searchArticulos(ORequest $req): void {
		$status = 'ok';
		$name = $req->getParamString('name');
		$id_marca = $req->getParamInt('idMarca');
		$list = [];

		if (is_null($name) || is_null($id_marca)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$list = $this->articulos_service->searchArticulos($name, $id_marca);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'model/articulo_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para buscar clientes
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/searchClientes')]
	public function searchClientes(ORequest $req): void {
		$status = 'ok';
		$name = $req->getParamString('name');
		$list = [];

		if (is_null($name)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$list = $this->clientes_service->searchClientes($name);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addComponent('list', 'model/cliente_list', ['list' => $list, 'extra' => 'nourlencode']);
	}

	/**
	 * Función para guardar un cliente
	 *
	 * @param ClienteDTO $data Objeto con toda la información sobre un cliente
	 * @return void
	 */
	#[ORoute('/saveCliente')]
	public function saveCliente(ClienteDTO $data): void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status=='ok') {
			$cliente = new Cliente();
			if (!is_null($data->getId())) {
				$cliente->find(['id' => $data->getId()]);
			}

			$cliente->set('nombre_apellidos',      urldecode($data->getNombreApellidos()));
			$cliente->set('dni_cif',               urldecode($data->getDniCif()));
			$cliente->set('telefono',              urldecode($data->getTelefono()));
			$cliente->set('email',                 urldecode($data->getEmail()));
			$cliente->set('direccion',             urldecode($data->getDireccion()));
			$cliente->set('poblacion',             urldecode($data->getPoblacion()));
			$cliente->set('provincia',             $data->getProvincia());
			$cliente->set('fact_igual',            $data->getFactIgual());
			$cliente->set('fact_nombre_apellidos', urldecode($data->getFactNombreApellidos()));
			$cliente->set('fact_dni_cif',          urldecode($data->getFactDniCif()));
			$cliente->set('fact_telefono',         urldecode($data->getFactTelefono()));
			$cliente->set('fact_email',            urldecode($data->getFactEmail()));
			$cliente->set('fact_direccion',        urldecode($data->getFactDireccion()));
			$cliente->set('fact_poblacion',        urldecode($data->getFactPoblacion()));
			$cliente->set('fact_provincia',        $data->getFactProvincia());
			$cliente->set('observaciones',         urldecode($data->getObservaciones()));

			$cliente->save();

			$data->setId( $cliente->get('id') );
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
