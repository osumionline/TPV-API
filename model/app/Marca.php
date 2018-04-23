<?php
class Marca extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'marca';
    $model = array(
        'id'          => array('type'=>Base::PK,                   'com'=>'Id único de cada marca'),
        'nombre'      => array('type'=>Base::TEXT,     'len'=>50,  'com'=>'Nombre de la marca'),
        'id_foto'     => array('type'=>Base::NUM,                  'com'=>'Id de la foto/logo de la marca'),
        'direccion'   => array('type'=>Base::TEXT,     'len'=>200, 'com'=>'Dirección de la marca'),
        'telefono'    => array('type'=>Base::TEXT,     'len'=>15,  'com'=>'Teléfono de la marca'),
        'fax'         => array('type'=>Base::TEXT,     'len'=>15,  'com'=>'Fax de la marca'),
        'email'       => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Dirección de email de la marca'),
        'web'         => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Dirección de la página web de la marca'),
        'notas'       => array('type'=>Base::LONGTEXT,             'com'=>'Notas/observaciones personales de la marca'),
        'created_at'  => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at'  => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}