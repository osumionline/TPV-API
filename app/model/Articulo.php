<?php
class Articulo extends OBase{
  function __construct(){
    $table_name = 'articulo';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada marca'
        ],
        'nombre' => [
          'type'     => Base::TEXT,
          'size'     => 100,
          'comment'  => 'Nombre del artículo',
          'nullable' => false
        ],
        'id_categoria' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la categoría a la que pertenece el artículo'
        ],
        'id_marca' => [
          'type'     => Base::NUM,
          'comment'  => 'Id de la marca del artículo',
          'nullable' => false
        ],
        'id_tipo' => [
          'type'    => Base::NUM,
          'comment' => 'Tipo de artículo'
        ],
        'referencia' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Referencia original del artículo'
        ],
        'puc' => [
          'type'    => Base::FLOAT,
          'comment' => 'Precio Unitario de Compra del artículo'
        ],
        'pvp' => [
          'type'    => Base::FLOAT,
          'comment' => 'PVP del artículo'
        ],
        'iva' => [
          'type'    => Base::FLOAT,
          'comment' => 'IVA del artículo'
        ],
        're' => [
          'type'    => Base::FLOAT,
          'comment' => 'Recargo de equivalencia del artículo'
        ],
        'margen' => [
          'type'    => Base::FLOAT,
          'comment' => 'Porcentaje de margen de beneficio del artículo'
        ],
        'stock' => [
          'type'    => Base::NUM,
          'comment' => 'Cantidad de unidades que hay del artículo'
        ],
        'stock_min' => [
          'type'    => Base::NUM,
          'comment' => 'Número mínimo de unidades que hay que tener del artículo'
        ],
        'stock_max' => [
          'type'    => Base::NUM,
          'comment' => 'Número máximo de unidades que hay que tener del artículo'
        ],
        'lote_optimo' => [
          'type'    => Base::NUM,
          'comment' => 'Número de unidades para hacer un pedido óptimo'
        ],
        'venta_online' => [
          'type'    => Base::BOOL,
          'comment' => 'Indica si el artículo está disponible para venta online 1 o no 0'
        ],
        'descripcion' => [
          'type'    => Base::LONGTEXT,
          'comment' => 'Descripción del artículo para venta online'
        ],
        'envio' => [
          'type'    => Base::NUM,
          'comment' => 'Tipo de envío para el artículo 0 envía 1 recoger'
        ],
        'estado' => [
          'type'    => Base::NUM,
          'comment' => 'Estado del artículo 0 activo 1 baja'
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