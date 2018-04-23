<?php
class CaracteristicaOpcion extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'caracteristica_opcion';
    $model = array(
        'id'                => array('type'=>Base::PK,                 'com'=>'Id único de cada opción'),
        'id_caracteristica' => array('type'=>Base::NUM,                'com'=>'Id de la característica que tiene la opción'),
        'opcion'            => array('type'=>Base::TEXT,    'len'=>50, 'com'=>'Opción de la característica'),
        'created_at'        => array('type'=>Base::CREATED,            'com'=>'Fecha de creación del registro'),
        'updated_at'        => array('type'=>Base::UPDATED,            'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}