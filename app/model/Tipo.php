<?php
class Tipo extends OBase{
  function __construct(){
    $table_name = 'tipo';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada tipo'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Nombre del tipo'
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