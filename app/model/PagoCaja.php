<?php
class PagoCaja extends OBase{
  function __construct(){
    $table_name  = 'pago_caja';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada pago de caja'
      ],
      'concepto' => [
        'type'    => Base::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 250,
        'comment' => 'Concepto del pago'
      ],
      'importe' => [
        'type'    => Base::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Importe de dinero sacado de la caja para realizar el pago'
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