<?php
class Cliente extends OBase{
  function __construct(){
    $table_name = 'cliente';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único para cada cliente'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Nombre del cliente'
        ],
        'apellidos' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Apellidos del cliente'
        ],
        'dni_cif' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'DNI/CIF del cliente'
        ],
        'telefono' => [
          'type'    => Base::TEXT,
          'size'    => 15,
          'comment' => 'Teléfono del cliente'
        ],
        'email' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Email del cliente'
        ],
        'direccion' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Dirección del cliente'
        ],
        'codigo_postal' => [
          'type'    => Base::TEXT,
          'size'    => 10,
          'comment' => 'Código postal del cliente'
        ],
        'poblacion' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Población del cliente'
        ],
        'provincia' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la provincia del cliente'
        ],
        'notas' => [
          'type'    => Base::LONGTEXT,
          'comment' => 'Campo libre para notas personales del cliente'
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