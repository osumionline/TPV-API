<?php
class CaracteristicaOpcion extends OBase{
  function __construct(){
    $table_name = 'caracteristica_opcion';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada opción'
        ],
        'id_caracteristica' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la característica que tiene la opción'
        ],
        'opcion' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Opción de la característica'
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