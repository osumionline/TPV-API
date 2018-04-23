<?php
class ProveedorMarca extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'proveedor_marca';
    $model = array(
        'id_proveedor' => array('type'=>Base::PK,      'com'=>'Id del proveedor'),
        'id_marca'     => array('type'=>Base::PK,      'com'=>'Id de la marca'),
        'created_at'   => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at'   => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model,array('id_proveedor','id_marca'));
  }
}