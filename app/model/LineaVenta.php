<?php
class LineaVenta extends OBase{
  function __construct(){
    $table_name = 'linea_venta';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada línea en una venta'
        ],
        'id_venta' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la venta a la que pertenece la línea'
        ],
        'id_articulo' => [
          'type'    => Base::NUM,
          'comment' => 'Id del artículo que se vende'
        ],
        'puc' => [
          'type'    => Base::FLOAT,
          'comment' => 'Precio de coste original del artículo'
        ],
        'pvp' => [
          'type'    => Base::FLOAT,
          'comment' => 'PVP del artículo al venderse'
        ],
        'iva' => [
          'type'    => Base::FLOAT,
          'comment' => 'IVA del artículo al venderse'
        ],
        're' => [
          'type'    => Base::FLOAT,
          'comment' => 'Recargo de equivalencia del artículo al venderse'
        ],
        'descuento' => [
          'type'    => Base::NUM,
          'comment' => 'Porcentaje de descuento al vender el artículo'
        ],
        'devuelto' => [
          'type'    => Base::NUM,
          'comment' => 'Cantidad de unidades devueltas del artículo'
        ],
        'unidades' => [
          'type'    => Base::NUM,
          'comment' => 'Número de unidades del artículo vendidas'
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