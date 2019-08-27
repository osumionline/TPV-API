<?php
class Marca extends OBase{
  function __construct(){
    $table_name  = 'marca';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada marca'
      ],
      'nombre' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre de la marca'
      ],
      'telefono' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 15,
        'comment' => 'Teléfono de la marca'
      ],
      'email' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección de email de la marca'
      ],
      'web' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección de la página web de la marca'
      ],
      'observaciones' => [
        'type'    => Base::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Observaciones o notas personales de la marca'
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