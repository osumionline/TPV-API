<?php
class Comercial extends OBase{
  function __construct(){
    $table_name = 'comercial';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada comercial'
        ],
        'id_proveedor' => [
          'type'    => Base::NUM,
          'comment' => 'Id del proveedor para el que trabaja el comercial'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Nombre y apellidos del comercial'
        ],
        'telefono' => [
          'type'    => Base::TEXT,
          'size'    => 15,
          'comment' => 'Teléfono del comercial'
        ],
        'email' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Dirección de email del comercial'
        ],
        'notas' => [
          'type'    => Base::LONGTEXT,
          'comment' => 'Notas/observaciones personales del comercial'
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