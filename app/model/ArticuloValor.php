<?php
class ArticuloValor extends OBase{
  function __construct(){
    $table_name = 'articulo_valor';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id única de la combinación de artículo/característica/opción'
        ],
        'id_articulo' => [
          'type'    => Base::NUM,
          'comment' => 'Id del artículo'
        ],
        'id_caracteristica' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la característica'
        ],
        'id_opcion' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la opción'
        ],
        'valor' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Valor de la combinación'
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