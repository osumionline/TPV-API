<?php
class Proveedor extends OBase{
  function __construct(){
    $table_name  = 'proveedor';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada proveedor'
      ],
      'nombre' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre del proveedor'
      ],
      'direccion' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección física del proveedor'
      ],
      'telefono' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 15,
        'comment' => 'Teléfono del proveedor'
      ],
      'email' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección de email del proveedor'
      ],
      'web' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección de la página web del proveedor'
      ],
      'observaciones' => [
        'type'    => Base::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Observaciones o notas personales del proveedor'
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