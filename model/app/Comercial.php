<?php
class Comercial extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'comercial';
    $model = array(
        'id'           => array('type'=>Base::PK,                   'com'=>'Id único de cada comercial'),
        'id_proveedor' => array('type'=>Base::NUM,                  'com'=>'Id del proveedor para el que trabaja el comercial'),
        'nombre'       => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Nombre y apellidos del comercial'),
        'telefono'     => array('type'=>Base::TEXT,     'len'=>15,  'com'=>'Teléfono del comercial'),
        'email'        => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Dirección de email del comercial'),
        'notas'        => array('type'=>Base::LONGTEXT,             'com'=>'Notas/observaciones personales del comercial'),
        'created_at'   => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at'   => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}