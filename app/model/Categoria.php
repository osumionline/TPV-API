<?php
class Categoria extends OBase{
  function __construct(){
    $table_name = 'categoria';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id única de cada categoría'
        ],
        'id_padre' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la categoría padre de una categoría'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Nombre de la categoría'
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