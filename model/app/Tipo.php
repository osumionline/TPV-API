<?php
class Tipo extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'tipo';
    $model = array(
        'id'         => array('type'=>Base::PK,                  'com'=>'Id único de cada tipo'),
        'nombre'     => array('type'=>Base::TEXT,     'len'=>50, 'com'=>'Nombre del tipo'),
        'created_at' => array('type'=>Base::CREATED,             'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED,             'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}