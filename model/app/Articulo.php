<?php
class Articulo extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'articulo';
    $model = array(
        'id'           => array('type'=>Base::PK,                   'com'=>'Id único de cada marca'),
        'nombre'       => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Nombre del artículo'),
        'id_categoria' => array('type'=>Base::NUM,                  'com'=>'Id de la categoría a la que pertenece el artículo'),
        'id_marca'     => array('type'=>Base::NUM,                  'com'=>'Id de la marca del artículo'),
        'id_tipo'      => array('type'=>Base::NUM,                  'com'=>'Tipo de artículo'),
        'referencia'   => array('type'=>Base::TEXT,     'len'=>50,  'com'=>'Referencia original del artículo'),
        'puc'          => array('type'=>Base::FLOAT,                'com'=>'Precio Unitario de Compra del artículo'),
        'pvp'          => array('type'=>Base::FLOAT,                'com'=>'PVP del artículo'),
        'iva'          => array('type'=>Base::FLOAT,                'com'=>'IVA del artículo'),
        're'           => array('type'=>Base::FLOAT,                'com'=>'Recargo de equivalencia del artículo'),
        'margen'       => array('type'=>Base::FLOAT,                'com'=>'Porcentaje de margen de beneficio del artículo'),
        'stock'        => array('type'=>Base::NUM,                  'com'=>'Cantidad de unidades que hay del artículo'),
        'stock_min'    => array('type'=>Base::NUM,                  'com'=>'Número mínimo de unidades que hay que tener del artículo'),
        'stock_max'    => array('type'=>Base::NUM,                  'com'=>'Número máximo de unidades que hay que tener del artículo'),
        'lote_optimo'  => array('type'=>Base::NUM,                  'com'=>'Número de unidades para hacer un pedido óptimo'),
        'venta_online' => array('type'=>Base::BOOL,                 'com'=>'Indica si el artículo está disponible para venta online 1 o no 0'),
        'descripcion'  => array('type'=>Base::LONGTEXT,             'com'=>'Descripción del artículo para venta online'),
        'envio'        => array('type'=>Base::NUM,                  'com'=>'Tipo de envío para el artículo 0 envía 1 recoger'),
        'estado'       => array('type'=>Base::NUM,                  'com'=>'Estado del artículo 0 activo 1 baja'),
        'notas'        => array('type'=>Base::LONGTEXT,             'com'=>'Notas/observaciones personales de la marca'),
        'created_at'   => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at'   => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}