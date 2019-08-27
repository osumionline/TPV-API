<?php
class LineaVenta extends OBase{
  function __construct(){
    $table_name  = 'linea_venta';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id única de cada línea de venta'
      ],
      'id_venta' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'venta.id',
        'comment' => 'Id de la venta a la que pertenece la línea'
      ],
      'id_articulo' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'articulo.id',
        'comment' => 'Id del artículo que está siendo vendido'
      ],
      'puc' => [
        'type'    => Base::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Precio Unitario de Compra del artículo en el momento de su venta'
      ],
      'pvp' => [
        'type'    => Base::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Precio de Venta al Público del artículo en el momento de su venta'
      ],
      'iva' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'IVA del artículo en el momento de su venta'
      ],
      'tipo_descuento' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Tipo de descuento 0 ninguno 1 porcentaje 2 importe'
      ],
      'descuento' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Porcentaje de descuento aplicado'
      ],
      'cantidad' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Cantidad de artículos vendidos'
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