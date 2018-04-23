<?php
class Tarjeta extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'tarjeta';
    $model = array(
        'id'          => array('type'=>Base::PK,                 'com'=>'Id único de cada tarjeta'),
        'nombre'      => array('type'=>Base::TEXT,    'len'=>50, 'com'=>'Nombre de la tarjeta'),
        'abreviatura' => array('type'=>Base::TEXT,    'len'=>10, 'com'=>'Abreviatura del nombre de la tarjeta'),
        'id_logo'     => array('type'=>Base::NUM,                'com'=>'Id de la imagen usada como logo de la tarjeta'),
        'id_icono'    => array('type'=>Base::NUM,                'com'=>'Id de la imagen usada como icono de la tarjeta'),
        'comision'    => array('type'=>Base::FLOAT,              'com'=>'Porcentaje de comisión que se cobra en cada venta'),
        'created_at'  => array('type'=>Base::CREATED,            'com'=>'Fecha de creación del registro'),
        'updated_at'  => array('type'=>Base::UPDATED,            'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}