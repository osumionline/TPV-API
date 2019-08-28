<?php
class CodigoBarras extends OBase{
  function __construct(){
    $table_name  = 'codigo_barras';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada código de barras'
      ],
      'id_articulo' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'articulo.id',
        'comment' => 'Id del artículo al que pertenece el código de barras'
      ],
      'codigo_barras' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Código de barras del artículo'
      ],
      'por_defecto' => [
        'type'    => Base::BOOL,
        'comment' => 'Indica si es el código de barras asignado por defecto por el TPV 1 o añadido a mano 1'
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