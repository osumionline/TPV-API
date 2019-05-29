<?php
class Caracteristica extends OBase{
  function __construct(){
    $table_name = 'caracteristica';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id única de cada característica'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Nombre de la característica'
        ],
        'orden' => [
          'type'    => Base::NUM,
          'comment' => 'Orden de la característica entre todas las de un tipo'
        ],
        'tipo' => [
          'type'    => Base::NUM,
          'comment' => 'Tipo de característica 0 texto 1 número unidades 2 número decimales 3 fecha 4 color'
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