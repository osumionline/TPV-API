<?php
class TipoCaracteristica extends OBase{
  function __construct(){
    $table_name = 'tipo_caracteristica';
    $model = [
        'id_tipo' => [
          'type'    => Base::PK,
          'comment' => 'Id del tipo'
        ],
        'id_caracteristica' => [
          'type'    => Base::PK,
          'comment' => 'Id de la característica'
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