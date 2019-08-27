<?php
class api extends OController{
  private $general_service;
  private $articulos_service;

  function __construct(){
    $this->general_service   = new generalService($this);
    $this->articulos_service = new articulosService($this);
  }

  /*
   * Función para obtener los datos iniciales de configuración y comprobar el cierre de caja
   */
  function checkStart($req){
    $status = 'ok';
    $date = Base::getParam('date', $req['url_params'], false);
    $opened   = 'false';
    $app_data = 'null';

    if ($date===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $opened   = $this->general_service->getOpened($date) ? 'true' : 'false';
      $app_data = $this->general_service->getAppData();
    }

    $this->getTemplate()->add('status',  $status);
    $this->getTemplate()->add('opened',  $opened);
    $this->getTemplate()->add('appData', $app_data, 'nourlencode');
  }

  /*
   * Función guardar los datos iniciales de configuración
   */
  function saveInstallation($req){
    $status = 'ok';
    $tipo_iva     = Base::getParam('tipoIva',     $req['url_params'], false);
    $iva_list     = Base::getParam('ivaList',     $req['url_params'], false);
    $margin_list  = Base::getParam('marginList',  $req['url_params'], false);
    $venta_online = Base::getParam('ventaOnline', $req['url_params'], false);
    $fecha_cad    = Base::getParam('fechaCad',    $req['url_params'], false);

    if ($tipo_iva===false || $iva_list===false || $margin_list===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $this->general_service->saveAppData($tipo_iva, $iva_list, $margin_list, $venta_online, $fecha_cad);
    }

    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para abrir la caja
   */
  function openBox($req){
    $status = 'ok';
    
    $caja = new Caja();
    $caja->set('apertura',        date('Y-m-d H:i:s', mktime()));
    $caja->set('cierre',          null);
    $caja->set('diferencoa',      null);
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
    if ($previous_caja->find(['id'=>$previous_id])){
      $caja->set('efectivo_apertura', $previous_caja->get('efectivo_cierre'));
      $caja->save();
    }
    
    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para obtener la lista de marcas
   */
  function getMarcas($req){
    $list = $this->articulos_service->getMarcas();
    
    $this->getTemplate()->addPartial('list', 'api/marcas_list', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para obtener la lista de proveedores
   */
  function getProveedores($req){
    $list = $this->articulos_service->getProveedores();
    
    $this->getTemplate()->addPartial('list', 'api/proveedores_list', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para obtener la lista de categorías
   */
  function getCategorias($req){
    $list = $this->articulos_service->getCategoryTree([]);

    $this->getTemplate()->addPartial('list', 'api/categorias_list', ['list'=>$list, 'extra'=>'nourlencode']);
  }

  /*
   * Función para dar de baja un artículo
   */
  function disableProduct($req){
    $status = 'ok';
    $id     = Base::getParam('id', $req['url_params'], false);
    
    if ($id===false){
      $status = 'error';
    }
    
    if ($status=='ok'){
      $art = new Articulo();
      if ($art->find(['id' => $id])){
        $art->set('active', false);
        $art->save();
      }
      else{
        $status = 'error';
      }
    }
    
    $this->getTemplate()->add('status', $status);
  }

  /*
   * Función para guardar una marca
   */
  function saveMarca($req){
    $status = 'ok';
    $id            = Base::getParam('id',            $req['url_params'], false);
    $nombre        = Base::getParam('nombre',        $req['url_params'], false);
    $telefono      = Base::getParam('telefono',      $req['url_params'], false);
    $email         = Base::getParam('email',         $req['url_params'], false);
    $web           = Base::getParam('web',           $req['url_params'], false);
    $observaciones = Base::getParam('observaciones', $req['url_params'], false);

    if ($nombre===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $marca = new Marca();
      if (!empty($id)){
        $marca->find(['id'=>$id]);
      }

      $marca->set('nombre',        $nombre);
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

  /*
   * Función para guardar un proveedor
   */
  function saveProveedor($req){
    $status = 'ok';

    $id            = Base::getParam('id',            $req['url_params'], false);
    $nombre        = Base::getParam('nombre',        $req['url_params'], false);
    $direccion     = Base::getParam('direccion',     $req['url_params'], false);
    $telefono      = Base::getParam('telefono',      $req['url_params'], false);
    $email         = Base::getParam('email',         $req['url_params'], false);
    $web           = Base::getParam('web',           $req['url_params'], false);
    $observaciones = Base::getParam('observaciones', $req['url_params'], false);
    $marcas        = Base::getParam('marcas',        $req['url_params'], false);

    if ($nombre===false){
      $status = 'error';
    }

    if ($status=='ok'){
      $proveedor = new Proveedor();
      if (!empty($id)){
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

  /*
   * Función para guardar un artículo
   */
  function saveArticulo($req){
    var_dump($req);
    exit();
    $status = 'ok';
    $id                 = Base::getParam('id',                $req['url_params'], false);
    $localizador         = Base::getParam('localizador',       $req['url_params'], false);
    $nombre              = Base::getParam('nombre',            $req['url_params'], false);
    $puc                 = Base::getParam('puc',               $req['url_params'], false);
    $pvp                 = Base::getParam('pvp',               $req['url_params'], false);
    $margen              = Base::getParam('margen',            $req['url_params'], false);
    $palb                = Base::getParam('palb',              $req['url_params'], false);
    $id_marca            = Base::getParam('idMarca',           $req['url_params'], false);
    $id_proveedor        = Base::getParam('idProveedor',       $req['url_params'], false);
    $stock               = Base::getParam('stock',             $req['url_params'], false);
    $stock_min           = Base::getParam('stockMin',          $req['url_params'], false);
    $stock_max           = Base::getParam('stockMax',          $req['url_params'], false);
    $lote_optimo         = Base::getParam('loteOptimo',        $req['url_params'], false);
    $iva                 = Base::getParam('iva',               $req['url_params'], false);
    $fecha_caducidad     = Base::getParam('fechaCaducidad',    $req['url_params'], false);
    $mostrar_fec_cad     = Base::getParam('mostrarFecCad',     $req['url_params'], false);
    $observaciones       = Base::getParam('observaciones',     $req['url_params'], false);
    $mostrar_obs_pedidos = Base::getParam('mostrarObsPedidos', $req['url_params'], false);
    $mostrar_obs_ventas  = Base::getParam('mostrarObsVentas',  $req['url_params'], false);
    $referencia          = Base::getParam('referencia',        $req['url_params'], false);
    $venta_online        = Base::getParam('ventaOnline',       $req['url_params'], false);
    $mostrar_en_web      = Base::getParam('mostrarEnWeb',      $req['url_params'], false);
    $id_categoria        = Base::getParam('idCategoria',       $req['url_params'], false);
    $desc_corta          = Base::getParam('descCorta',         $req['url_params'], false);
    $desc                = Base::getParam('desc',              $req['url_params'], false);
    $codigos_barras      = Base::getParam('codigosBarras',     $req['url_params'], false);
    $activo              = Base::getParam('activo',            $req['url_params'], false);
    
    if ($nombre===false || $id_marca===false || $iva===false){
      $status = 'error';
    }
    
    if ($status=='ok'){
      $art = new Articulo();
      if ($id!==false){
        $art->find(['id'=>$id]);
      }
      else{
        $localizador = $this->articulos_service->getNewLocalizador();
      }
      $art->set('localizador', $localizador);
      $art->set('nombre', $nombre);
      $art->set('puc', $puc);
      $art->set('pvp', $pvp);
      $art->set('margen', $margen);
      $art->set('palb', $palb);
      $art->set('id_marca', $id_marca);
      $art->set('id_proveedor', $id_proveedor);
      $art->set('stock', $stock);
      $art->set('stock_min', $stock_min);
      $art->set('stock_max', $stock_max);
      $art->set('lote_optimo', $lote_optimo);
      $art->set('iva', $iva);
      $art->set('fecha_caducidad', $fecha_caducidad);
      $art->set('mostrar_fec_cad', $mostrar_fec_cad);
      $art->set('observaciones', $observaciones);
      $art->set('mostrar_obs_pedidos', $mostrar_obs_pedidos);
      $art->set('mostrar_obs_ventas', $mostrar_obs_ventas);
      $art->set('referencia', $referencia);
      $art->set('venta_online', $venta_online);
      $art->set('mostrar_en_web', $mostrar_en_web);
      $art->set('id_categoria', $id_categoria);
      $art->set('desc_corta', $desc_corta);
      $art->set('desc', $desc);
      $art->set('activo', $activo);
    }
    
    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->add('localizador', $localizador);
  }
}