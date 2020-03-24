<?php
class Proveedor extends OModel{
  function __construct(){
    $table_name  = 'proveedor';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id único para cada proveedor'
      ],
      'nombre' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre del proveedor'
      ],
      'direccion' => [
        'type'    => OCore::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección física del proveedor'
      ],
      'telefono' => [
        'type'    => OCore::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 15,
        'comment' => 'Teléfono del proveedor'
      ],
      'email' => [
        'type'    => OCore::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección de email del proveedor'
      ],
      'web' => [
        'type'    => OCore::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección de la página web del proveedor'
      ],
      'observaciones' => [
        'type'    => OCore::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Observaciones o notas personales del proveedor'
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