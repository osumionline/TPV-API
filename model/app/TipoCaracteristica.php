<?php
class TipoCaracteristica extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'tipo_caracteristica';
    $model = array(
        'id_tipo'           => array('type'=>Base::PK,      'com'=>'Id del tipo'),
        'id_caracteristica' => array('type'=>Base::PK,      'com'=>'Id de la característica'),
        'created_at'        => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at'        => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model,array('id_tipo','id_caracteristica'));
  }
}