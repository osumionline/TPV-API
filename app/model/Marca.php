<?php
class Marca extends OBase{
  function __construct(){
    $table_name = 'marca';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada marca'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Nombre de la marca'
        ],
        'id_foto' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la foto/logo de la marca'
        ],
        'direccion' => [
          'type'    => Base::TEXT,
          'size'    => 200,
          'comment' => 'Dirección de la marca'
        ],
        'telefono' => [
          'type'    => Base::TEXT,
          'size'    => 15,
          'comment' => 'Teléfono de la marca'
        ],
        'fax' => [
          'type'    => Base::TEXT,
          'size'    => 15,
          'comment' => 'Fax de la marca'
        ],
        'email' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Dirección de email de la marca'
        ],
        'web' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Dirección de la página web de la marca'
        ],
        'notas' => [
          'type'    => Base::LONGTEXT,
          'comment' => 'Notas/observaciones personales de la marca'
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