<?php
class LineaVenta extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'linea_venta';
    $model = array(
        'id'          => array('type'=>Base::PK,      'com'=>'Id único de cada línea en una venta'),
        'id_venta'    => array('type'=>Base::NUM,     'com'=>'Id de la venta a la que pertenece la línea'),
        'id_articulo' => array('type'=>Base::NUM,     'com'=>'Id del artículo que se vende'),
        'puc'         => array('type'=>Base::FLOAT,   'com'=>'Precio de coste original del artículo'),
        'pvp'         => array('type'=>Base::FLOAT,   'com'=>'PVP del artículo al venderse'),
        'iva'         => array('type'=>Base::FLOAT,   'com'=>'IVA del artículo al venderse'),
        're'          => array('type'=>Base::FLOAT,   'com'=>'Recargo de equivalencia del artículo al venderse'),
        'descuento'   => array('type'=>Base::NUM,     'com'=>'Porcentaje de descuento al vender el artículo'),
        'devuelto'    => array('type'=>Base::NUM,     'com'=>'Cantidad de unidades devueltas del artículo'),
        'unidades'    => array('type'=>Base::NUM,     'com'=>'Número de unidades del artículo vendidas'),
        'created_at'  => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at'  => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}