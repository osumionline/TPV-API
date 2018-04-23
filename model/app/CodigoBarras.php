<?php
class CodigoBarras extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'codigo_barras';
    $model = array(
        'id'            => array('type'=>Base::PK,                 'com'=>'Id único para cada código de barras'),
        'id_articulo'   => array('type'=>Base::NUM,                'com'=>'Id del artículo al que pertenece el código de barras'),
        'codigo_barras' => array('type'=>Base::TEXT,    'len'=>50, 'com'=>'Código de barras del artículo'),
        'created_at'    => array('type'=>Base::CREATED,            'com'=>'Fecha de creación del registro'),
        'updated_at'    => array('type'=>Base::UPDATED,            'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}