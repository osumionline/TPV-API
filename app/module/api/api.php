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
use OsumiFramework\App\Service\generalService;
use OsumiFramework\App\Service\articulosService;

#[ORoute(
	type: 'json',
	prefix: '/api'
)]
class api extends OModule {
	private ?generalService $general_service = null;
	private ?articulosService $articulos_service = null;

	function __construct() {
		$this->general_service   = new generalService();
		$this->articulos_service = new articulosService();
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
		$tarjetas = [];

		if (is_null($date)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$opened   = $this->general_service->getOpened($date) ? 'true' : 'false';
			$app_data = $this->general_service->getAppData();
			$tarjetas = $this->general_service->getTarjetas();
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('opened',  $opened);
		$this->getTemplate()->add('appData', $app_data, 'nourlencode');
		$this->getTemplate()->addComponent('tarjetas', 'model/tarjeta_list', ['list' => $tarjetas, 'extra' => 'nourlencode']);
	}

	/**
	 * Función guardar los datos iniciales de configuración
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/saveInstallation')]
	public function saveInstallation(ORequest $req): void {
		$status = 'ok';
		$tipo_iva     = $req->getParamString('tipoIva');
		$iva_list     = $req->getParam('ivaList');
		$margin_list  = $req->getParam('marginList');
		$venta_online = $req->getParamBool('ventaOnline', false);
		$fecha_cad    = $req->getParamBool('fechaCad', false);

		if (is_null($tipo_iva) || is_null($iva_list) || is_null($margin_list)){
			$status = 'error';
		}

		if ($status=='ok'){
			$this->general_service->saveAppData($tipo_iva, $iva_list, $margin_list, $venta_online, $fecha_cad);
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
		$caja->set('apertura',        date('Y-m-d H:i:s', time()));
		$caja->set('cierre',          null);
		$caja->set('diferencia',      null);
		$caja->set('ventas',          null);
		$caja->set('beneficios',      null);
		$caja->set('venta_efectivo',  null);
		$caja->set('venta_tarjetas',  null);
		$caja->set('efectivo_cierre', null);

		$caja->set('1c',   null);
		$caja->set('2c',   null);
		$caja->set('5c',   null);
		$caja->set('10c',  null);
		$caja->set('20c',  null);
		$caja->set('50c',  null);
		$caja->set('1e',   null);
		$caja->set('2e',   null);
		$caja->set('5e',   null);
		$caja->set('10e',  null);
		$caja->set('20e',  null);
		$caja->set('50e',  null);
		$caja->set('100e', null);
		$caja->set('200e', null);
		$caja->set('500e', null);

		$caja->save();

		$previous_id = $caja->get('id') -1;
		$previous_caja = new Caja();
		if ($previous_caja->find(['id'=>$previous_id])) {
			$caja->set('efectivo_apertura', $previous_caja->get('efectivo_cierre'));
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
				$art->set('active', false);
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
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/saveMarca')]
	public function saveMarca(ORequest $req): void {
		$status = 'ok';
		$id            = $req->getParamInt('id');
		$nombre        = $req->getParamString('nombre');
		$direccion     = $req->getParamString('direccion');
		$telefono      = $req->getParamString('telefono');
		$email         = $req->getParamString('email');
		$web           = $req->getParamString('web');
		$observaciones = $req->getParamString('observaciones');

		if (is_null($nombre)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$marca = new Marca();
			if (!is_null($id)) {
				$marca->find(['id'=>$id]);
			}

			$marca->set('nombre',        $nombre);
			$marca->set('direccion',     $direccion);
			$marca->set('telefono',      $telefono);
			$marca->set('email',         $email);
			$marca->set('web',           $web);
			$marca->set('observaciones', $observaciones);

			$marca->save();

			$id = $marca->get('id');
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($id) ? 'null' : $id);
	}

	/**
	 * Función para guardar un proveedor
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/saveProveedor')]
	public function saveProveedor(ORequest $req): void {
		$status = 'ok';

		$id            = $req->getParamInt('id');
		$nombre        = $req->getParamString('nombre');
		$direccion     = $req->getParamString('direccion');
		$telefono      = $req->getParamString('telefono');
		$email         = $req->getParamString('email');
		$web           = $req->getParamString('web');
		$observaciones = $req->getParamString('observaciones');
		$marcas        = $req->getParam('marcas');

		if (is_null($nombre)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$proveedor = new Proveedor();
			if (!is_null($id)) {
				$proveedor->find(['id'=>$id]);
			}

			$proveedor->set('nombre',        $nombre);
			$proveedor->set('direccion',     $direccion);
			$proveedor->set('telefono',      $telefono);
			$proveedor->set('email',         $email);
			$proveedor->set('web',           $web);
			$proveedor->set('observaciones', $observaciones);

			$proveedor->save();

			$this->articulos_service->updateProveedoresMarcas($proveedor->get('id'), $marcas);

			$id = $proveedor->get('id');
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($id) ? 'null' : $id);
	}

	/**
	 * Función para guardar un artículo
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/saveArticulo')]
	public function saveArticulo(ORequest $req): void {
		$status = 'ok';
		$id                  = $req->getParamInt('id');
		$localizador         = $req->getParamInt('localizador');
		$nombre              = $req->getParamString('nombre');
		$puc                 = $req->getParamFloat('puc');
		$pvp                 = $req->getParamFloat('pvp');
		$margen              = $req->getParamFloat('margen');
		$palb                = $req->getParamFloat('palb');
		$id_marca            = $req->getParamInt('idMarca');
		$id_proveedor        = $req->getParamInt('idProveedor');
		$stock               = $req->getParamInt('stock');
		$stock_min           = $req->getParamInt('stockMin');
		$stock_max           = $req->getParamInt('stockMax');
		$lote_optimo         = $req->getParamInt('loteOptimo');
		$iva                 = $req->getParamInt('iva');
		$fecha_caducidad     = $req->getParamString('fechaCaducidad');
		$mostrar_feccad      = $req->getParamBool('mostrarFecCad');
		$observaciones       = $req->getParamString('observaciones');
		$mostrar_obs_pedidos = $req->getParamBool('mostrarObsPedidos');
		$mostrar_obs_ventas  = $req->getParamBool('mostrarObsVentas');
		$referencia          = $req->getParamString('referencia');
		$venta_online        = $req->getParamBool('ventaOnline');
		$mostrar_en_web      = $req->getParamBool('mostrarEnWeb');
		$id_categoria        = $req->getParamInt('idCategoria');
		$desc_corta          = $req->getParamString('descCorta');
		$desc                = $req->getParamString('desc');
		$codigos_barras      = $req->getParam('codigosBarras');
		$activo              = $req->getParamBool('activo');

		if (is_null($nombre) || is_null($id_marca) || is_null($iva)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$art = new Articulo();
			if (!is_null($id)) {
				$art->find(['id'=>$id]);
			}
			else {
				$localizador = $this->articulos_service->getNewLocalizador();
			}
			$feccad = null;
			if ($mostrar_feccad) {
				$arr_feccad = explode('/', $fecha_caducidad);
				$feccad = $arr_feccad[1] . '-' . $arr_feccad[0]. '-01 00:00:00';
			}
			$art->set('localizador',         $localizador);
			$art->set('nombre',              $nombre);
			$art->set('slug',                OTools::slugify($nombre));
			$art->set('puc',                 $puc);
			$art->set('pvp',                 $pvp);
			$art->set('margen',              $margen);
			$art->set('palb',                $palb);
			$art->set('id_marca',            $id_marca);
			$art->set('id_proveedor',        $id_proveedor);
			$art->set('stock',               $stock);
			$art->set('stock_min',           $stock_min);
			$art->set('stock_max',           $stock_max);
			$art->set('lote_optimo',         $lote_optimo);
			$art->set('iva',                 $iva);
			$art->set('fecha_caducidad',     $feccad);
			$art->set('mostrar_feccad',      $mostrar_feccad);
			$art->set('observaciones',       $observaciones);
			$art->set('mostrar_obs_pedidos', $mostrar_obs_pedidos);
			$art->set('mostrar_obs_ventas',  $mostrar_obs_ventas);
			$art->set('referencia',          $referencia);
			$art->set('venta_online',        $venta_online);
			$art->set('mostrar_en_web',      $mostrar_en_web);
			$art->set('id_categoria',        $id_categoria);
			$art->set('desc_corta',          $desc_corta);
			$art->set('desc',                $desc);
			$art->set('activo',              $activo);

			$art->save();
			$id = $art->get('id');

			$cod_barras_por_defecto = false;
			foreach ($codigos_barras as $cod) {
				if (!empty($cod['codigoBarras'])) {
					$cb = new CodigoBarras();
					if (!empty($cod['id'])) {
						$cb->find(['id'=>$cod['id']]);
					}
					$cb->set('id_articulo', $id);
					$cb->set('codigo_barras', $cod['codigoBarras']);
					if ($cb->get('por_defecto')) {
						$cod_barras_por_defecto = true;
					}
					$cb->save();
				}
			}

			if (!$cod_barras_por_defecto) {
				$cb = new CodigoBarras();
				$cb->set('id_articulo', $id);
				$cb->set('codigo_barras', $localizador);
				$cb->set('por_defecto', true);
				$cb->save();
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('localizador', $localizador);
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
				$articulo->find(['id'=>$cb->get('id_articulo')]);
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
}
