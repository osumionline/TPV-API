<?php
class ArticuloFoto extends OBase{
  function __construct(){
    $table_name = 'articulo_foto';
    $model = [
        'id_articulo' => [
          'type'    => Base::PK,
          'comment' => 'Id del artículo'
        ],
        'id_foto' => [
          'type'    => Base::PK,
          'comment' => 'Id de la foto'
        ],
        'orden' => [
          'type'    => Base::NUM,
          'comment' => 'Orden de la foto entre todas las de un producto'
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