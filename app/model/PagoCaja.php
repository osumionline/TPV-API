<?php
class PagoCaja extends OBase{
  function __construct(){
    $table_name = 'pago_caja';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada pago'
        ],
        'concepto' => [
          'type'    => Base::TEXT,
          'size'    => 100,
          'comment' => 'Concepto del pago'
        ],
        'importe' => [
          'type'    => Base::FLOAT,
          'comment' => 'Importe pagado'
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