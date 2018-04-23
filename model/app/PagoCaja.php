<?php
class PagoCaja extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'pago_caja';
    $model = array(
        'id'         => array('type'=>Base::PK,                   'com'=>'Id único de cada pago'),
        'concepto'   => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Concepto del pago'),
        'importe'    => array('type'=>Base::FLOAT,                'com'=>'Importe pagado'),
        'created_at' => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}