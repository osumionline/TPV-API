<?php
class Cliente extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'cliente';
    $model = array(
        'id'            => array('type'=>Base::PK,                   'com'=>'Id único para cada cliente'),
        'nombre'        => array('type'=>Base::TEXT,     'len'=>50,  'com'=>'Nombre del cliente'),
        'apellidos'     => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Apellidos del cliente'),
        'dni_cif'       => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'DNI/CIF del cliente'),
        'telefono'      => array('type'=>Base::TEXT,     'len'=>15,  'com'=>'Teléfono del cliente'),
        'email'         => array('type'=>Base::TEXT,     'len'=>50,  'com'=>'Email del cliente'),
        'direccion'     => array('type'=>Base::TEXT,     'len'=>100, 'com'=>'Dirección del cliente'),
        'codigo_postal' => array('type'=>Base::TEXT,     'len'=>10,  'com'=>'Código postal del cliente'),
        'poblacion'     => array('type'=>Base::TEXT,     'len'=>50,  'com'=>'Población del cliente'),
        'provincia'     => array('type'=>Base::NUM,                  'com'=>'Id de la provincia del cliente'),
        'notas'         => array('type'=>Base::LONGTEXT,             'com'=>'Campo libre para notas personales del cliente'),
        'created_at'    => array('type'=>Base::CREATED,              'com'=>'Fecha de creación del registro'),
        'updated_at'    => array('type'=>Base::UPDATED,              'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}