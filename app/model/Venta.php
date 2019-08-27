<?php
class Venta extends OBase{
  function __construct(){
    $table_name  = 'venta';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único de cada venta'
      ],
      'id_cliente' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => null,
        'ref' => 'cliente.id',
        'comment' => 'Id del cliente'
      ],
      'total' => [
        'type'    => Base::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Importe total de la venta'
      ],
      'entregado' => [
        'type'    => Base::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Importe entregado por el cliente'
      ],
      'tipo_pago' => [
        'type'    => Base::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Tipo de pago 0 metálico 1 tarjeta 2 web'
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