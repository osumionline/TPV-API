<?php
class api extends OController{
  private $general_service;
  private $articulos_service;

  function __construct(){
    $this->general_service   = new generalService();
    $this->articulos_service = new articulosService();
  }

  /*
   * Función para obtener los datos iniciales de configuración y comprobar el cierre de caja
   */
  function checkStart($req){
    $status = 'ok';
    $date = OTools::getParam('date', $req['params'], false);
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
    $tipo_iva     = OTools::getParam('tipoIva',     $req['params'], false);
    $iva_list     = OTools::getParam('ivaList',     $req['params'], false);
    $margin_list  = OTools::getParam('marginList',  $req['params'], false);
    $venta_online = OTools::getParam('ventaOnline', $req['params'], false);
    $fecha_cad    = OTools::getParam('fechaCad',    $req['params'], false);

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
    $id     = OTools::getParam('id', $req['params'], false);
    
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
    $id            = OTools::getParam('id',            $req['params'], false);
    $nombre        = OTools::getParam('nombre',        $req['params'], false);
    $telefono      = OTools::getParam('telefono',      $req['params'], false);
    $email         = OTools::getParam('email',         $req['params'], false);
    $web           = OTools::getParam('web',           $req['params'], false);
    $observaciones = OTools::getParam('observaciones', $req['params'], false);

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

    $id            = OTools::getParam('id',            $req['params'], false);
    $nombre        = OTools::getParam('nombre',        $req['params'], false);
    $direccion     = OTools::getParam('direccion',     $req['params'], false);
    $telefono      = OTools::getParam('telefono',      $req['params'], false);
    $email         = OTools::getParam('email',         $req['params'], false);
    $web           = OTools::getParam('web',           $req['params'], false);
    $observaciones = OTools::getParam('observaciones', $req['params'], false);
    $marcas        = OTools::getParam('marcas',        $req['params'], false);

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
    $status = 'ok';
    $id                 = OTools::getParam('id',                $req['params'], false);
    $localizador         = OTools::getParam('localizador',       $req['params'], false);
    $nombre              = OTools::getParam('nombre',            $req['params'], false);
    $puc                 = OTools::getParam('puc',               $req['params'], false);
    $pvp                 = OTools::getParam('pvp',               $req['params'], false);
    $margen              = OTools::getParam('margen',            $req['params'], false);
    $palb                = OTools::getParam('palb',              $req['params'], false);
    $id_marca            = OTools::getParam('idMarca',           $req['params'], false);
    $id_proveedor        = OTools::getParam('idProveedor',       $req['params'], false);
    $stock               = OTools::getParam('stock',             $req['params'], false);
    $stock_min           = OTools::getParam('stockMin',          $req['params'], false);
    $stock_max           = OTools::getParam('stockMax',          $req['params'], false);
    $lote_optimo         = OTools::getParam('loteOptimo',        $req['params'], false);
    $iva                 = OTools::getParam('iva',               $req['params'], false);
    $fecha_caducidad     = OTools::getParam('fechaCaducidad',    $req['params'], false);
    $mostrar_feccad      = OTools::getParam('mostrarFecCad',     $req['params'], false);
    $observaciones       = OTools::getParam('observaciones',     $req['params'], false);
    $mostrar_obs_pedidos = OTools::getParam('mostrarObsPedidos', $req['params'], false);
    $mostrar_obs_ventas  = OTools::getParam('mostrarObsVentas',  $req['params'], false);
    $referencia          = OTools::getParam('referencia',        $req['params'], false);
    $venta_online        = OTools::getParam('ventaOnline',       $req['params'], false);
    $mostrar_en_web      = OTools::getParam('mostrarEnWeb',      $req['params'], false);
    $id_categoria        = OTools::getParam('idCategoria',       $req['params'], false);
    $desc_corta          = OTools::getParam('descCorta',         $req['params'], false);
    $desc                = OTools::getParam('desc',              $req['params'], false);
    $codigos_barras      = OTools::getParam('codigosBarras',     $req['params'], false);
    $activo              = OTools::getParam('activo',            $req['params'], false);
    
    if ($nombre===false || $id_marca===false || $iva===false){
      $status = 'error';
    }
    
    if ($status=='ok'){
      $art = new Articulo();
      if (!empty($id)){
        $art->find(['id'=>$id]);
      }
      else{
        $localizador = $this->articulos_service->getNewLocalizador();
      }
      $feccad = null;
      if ($mostrar_feccad){
        $arr_feccad = explode('/', $fecha_caducidad);
        $feccad = $arr_feccad[1] . '-' . $arr_feccad[0]. '-01 00:00:00';
      }
      $art->set('localizador',         $localizador);
      $art->set('nombre',              $nombre);
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
      foreach ($codigos_barras as $cod){
        if (!empty($cod['codigoBarras'])){
          $cb = new CodigoBarras();
          if (!empty($cod['id'])){
            $cb->find(['id'=>$cod['id']]);
          }
          $cb->set('id_articulo', $id);
          $cb->set('codigo_barras', $cod['codigoBarras']);
          if ($cb->get('por_defecto')){
            $cod_barras_por_defecto = true;
          }
          $cb->save();
        }
      }
      
      if (!$cod_barras_por_defecto){
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

  /*
   * Función para obtener los datos de un artículo
   */
  function loadArticulo($req){
    $status = 'ok';
    $localizador = OTools::getParam('localizador', $req['params'], false);
    $articulo    = null;

    if ($localizador===false){
      $status = 'error';
    }
    
    if ($status=='ok'){
      $cb = new CodigoBarras();
      if ($cb->find(['codigo_barras'=>$localizador])){
        $articulo = new Articulo();
        $articulo->find(['id'=>$cb->get('id_articulo')]);
      }
      else{
        $status = 'error';
      }
    }
    
    $this->getTemplate()->add('status', $status);
    $this->getTemplate()->addPartial('articulo', 'api/articulo', ['articulo'=>$articulo, 'extra'=>'nourlencode']);
  }
}