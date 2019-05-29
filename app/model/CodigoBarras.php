<?php
class CodigoBarras extends OBase{
  function __construct(){
    $table_name = 'codigo_barras';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único para cada código de barras'
        ],
        'id_articulo' => [
          'type'    => Base::NUM,
          'comment' => 'Id del artículo al que pertenece el código de barras'
        ],
        'codigo_barras' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Código de barras del artículo'
        ],
        'created_at' => [
          'type'    => Base::CREATED,
          'comment' => 'Fecha de creación del registro'
        ],
        'updated_at' => [
          'type'    => Base::UPDATED,
          'comment' => 'Fecha de última modificación del registro'
        ]
    ];

    parent::load($table_name, $model);
  }
}