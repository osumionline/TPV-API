<?php
class Foto extends OBase{
  function __construct(){
    $table_name = 'foto';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id única para cada foto'
        ],
        'ext' => [
          'type'    => Base::TEXT,
          'size'    => 5,
          'comment' => 'Extensión del archivo de la foto'
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