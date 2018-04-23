<?php
class Foto extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'foto';
    $model = array(
        'id'         => array('type'=>Base::PK,                 'com'=>'Id única para cada foto'),
        'ext'        => array('type'=>Base::TEXT,     'len'=>5, 'com'=>'Extensión del archivo de la foto'),
        'created_at' => array('type'=>Base::CREATED,            'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED,            'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}