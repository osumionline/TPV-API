<?php
class Proveedor extends OBase{
  function __construct(){
    $table_name = 'proveedor';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada proveedor'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Nombre del proveedor'
        ],
        'id_foto' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la foto/logo del proveedor'
        ],
        'direccion' => [
          'type'    => Base::TEXT,
          'size'    => 200,
          'comment' => 'Dirección del proveedor'
        ],
        'telefono' => [
          'type'    => Base::TEXT,
          'size'    => 15,
          'comment' => 'Teléfono del proveedor'
        ],
        'fax' => [
          'type'    => Base::TEXT,
          'size'    => 15,
          'comment' => 'Fax del proveedor'
        ],
        'email' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Dirección de email del proveedor'
        ],
        'web' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Dirección de la página web del proveedor'
        ],
        'notas' => [
          'type'    => Base::LONGTEXT,
          'comment' => 'Notas/observaciones personales del proveedor'
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