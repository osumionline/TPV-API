<?php
class ArticuloFoto extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'articulo_foto';
    $model = array(
        'id_articulo' => array('type'=>Base::PK,      'com'=>'Id del artículo'),
        'id_foto'     => array('type'=>Base::PK,      'com'=>'Id de la foto'),
        'orden'       => array('type'=>Base::NUM,     'com'=>'Orden de la foto entre todas las de un producto'),
        'created_at'  => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at'  => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model,array('id_articulo','id_foto'));
  }
}