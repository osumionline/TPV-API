<?php
class Foto extends OBase{
  function __construct(){
    $table_name  = 'foto';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada foto'
      ],
      'id_articulo' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'articulo.id',
        'comment' => 'Id del artículo al que pertenece la foto'
      ],
      'ext' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 5,
        'comment' => 'Extensión del archivo de la foto'
      ],
      'orden' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Orden de la foto entre todas las fotos de un artículo'
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