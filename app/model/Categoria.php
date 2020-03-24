<?php
class Categoria extends OModel{
  function __construct(){
    $table_name  = 'categoria';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id único para cada categoría'
      ],
      'id_padre' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Id de la categoría padre en caso de ser una subcategoría'
      ],
      'nombre' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre de la categoría'
      ],
      'created_at' => [
        'type'    => OCore::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => OCore::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }
}