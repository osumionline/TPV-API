<?php
class Caracteristica extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'caracteristica';
    $model = array(
        'id'         => array('type'=>Base::PK,                  'com'=>'Id única de cada característica'),
        'nombre'     => array('type'=>Base::TEXT,     'len'=>50, 'com'=>'Nombre de la característica'),
        'orden'      => array('type'=>Base::NUM,                 'com'=>'Orden de la característica entre todas las de un tipo'),
        'tipo'       => array('type'=>Base::NUM,                 'com'=>'Tipo de característica 0 texto 1 número unidades 2 número decimales 3 fecha 4 color'),
        'created_at' => array('type'=>Base::CREATED,             'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED,             'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}