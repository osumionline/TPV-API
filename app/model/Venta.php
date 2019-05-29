<?php
class Venta extends OBase{
  function __construct(){
    $table_name = 'venta';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada venta'
        ],
        'id_cliente' => [
          'type'    => Base::NUM,
          'comment' => 'Id del cliente en caso de que sea una venta a un cliente fichado'
        ],
        'total' => [
          'type'    => Base::NUM,
          'comment' => 'Importe total de la venta'
        ],
        'entregado' => [
          'type'    => Base::FLOAT,
          'comment' => 'Importe entregado por el cliente'
        ],
        'tipo_pago' => [
          'type'    => Base::NUM,
          'comment' => 'Tipo de pago 0 metálico 1 tarjeta 2 vale 3 web'
        ],
        'id_tarjeta' => [
          'type'    => Base::NUM,
          'comment' => 'Id del tipo de tarjeta usada en el pago'
        ],
        'saldo' => [
          'type'    => Base::FLOAT,
          'comment' => 'Saldo en caso de que el ticket sea un vale'
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