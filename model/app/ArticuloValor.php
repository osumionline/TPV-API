<?php
class ArticuloValor extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'articulo_valor';
    $model = array(
        'id'                => array('type'=>Base::PK,                 'com'=>'Id única de la combinación de artículo/característica/opción'),
        'id_articulo'       => array('type'=>Base::NUM,                'com'=>'Id del artículo'),
        'id_caracteristica' => array('type'=>Base::NUM,                'com'=>'Id de la característica'),
        'id_opcion'         => array('type'=>Base::NUM,                'com'=>'Id de la opción'),
        'valor'             => array('type'=>Base::TEXT,    'len'=>50, 'com'=>'Valor de la combinación'),
        'created_at'        => array('type'=>Base::CREATED,            'com'=>'Fecha de creación del registro'),
        'updated_at'        => array('type'=>Base::UPDATED,            'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}