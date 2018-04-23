<?php
class Proveedor extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'proveedor';
    $model = array(
        'id'          => array('type'=>Base::PK,                   'com'=>'Id único de cada proveedor'),
        'nombre'      => array('type'=>Base::TEXT,     'len'=>50,  'com'=>'Nombre del proveedor'),
        'id_foto'     => array('type'=>Base::NUM,                  'com'=>'Id de la foto/logo del proveedor'),
        'direccion'   => array('type'=>Base::TEXT,     'len'=>200, 'com'=>'Dirección del proveedor'),
        'telefono'    => array('type'=>Base::TEXT,     'len'=>15,  'com'=>'Teléfono del proveedor'),
        'fax'         => array('type'=>Base::TEXT,     'len'=>15,  'com'=>'Fax del proveedor'),
        'email'       => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Dirección de email del proveedor'),
        'web'         => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Dirección de la página web del proveedor'),
        'notas'       => array('type'=>Base::LONGTEXT,             'com'=>'Notas/observaciones personales del proveedor'),
        'created_at'  => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at'  => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}