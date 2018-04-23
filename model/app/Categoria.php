<?php
class Categoria extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'categoria';
    $model = array(
        'id'         => array('type'=>Base::PK,                   'com'=>'Id única de cada categoría'),
        'id_padre'   => array('type'=>Base::NUM,                  'com'=>'Id de la categoría padre de una categoría'),
        'nombre'     => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Nombre de la categoría'),
        'created_at' => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}