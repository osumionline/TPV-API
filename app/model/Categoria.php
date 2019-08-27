<?php
class Categoria extends OBase{
  function __construct(){
    $table_name  = 'categoria';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada categoría'
      ],
      'id_padre' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Id de la categoría padre en caso de ser una subcategoría'
      ],
      'nombre' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre de la categoría'
      ],
      'created_at' => [
        'type'    => Base::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => Base::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }
}