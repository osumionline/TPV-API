<?php
class Cliente extends OBase{
  function __construct(){
    $table_name  = 'cliente';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada cliente'
      ],
      'nombre' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 50,
        'comment' => 'Nombre del cliente'
      ],
      'apellidos' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Apellidos del cliente'
      ],
      'dni_cif' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 10,
        'comment' => 'DNI/CIF del cliente'
      ],
      'telefono' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 15,
        'comment' => 'Teléfono del cliente'
      ],
      'email' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Email del cliente'
      ],
      'direccion' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 100,
        'comment' => 'Dirección del cliente'
      ],
      'codigo_postal' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 10,
        'comment' => 'Código postal del cliente'
      ],
      'poblacion' => [
        'type'    => Base::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 50,
        'comment' => 'Población del cliente'
      ],
      'provincia' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'comment' => 'Id de la provincia del cliente'
      ],
      'observaciones' => [
        'type'    => Base::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Campo libre para observaciones personales del cliente'
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