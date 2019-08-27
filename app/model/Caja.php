<?php
class Caja extends OBase{
  function __construct(){
    $table_name  = 'caja';
    $model = [
      'id' => [
        'type'    => Base::PK,
        'comment' => 'Id único para cada cierre de caja'
      ],
      'apertura' => [
        'type'    => Base::DATE,
        'nullable' => false,
        'default' => null,
        'comment' => 'Fecha de apertura de la caja'
      ],
      'cierre' => [
        'type'    => Base::DATE,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de cierre de la caja'
      ],
      'diferencia' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Diferencia entre el importe que debería haber y el que realmente hay'
      ],
      'ventas' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total de ventas para el período de la caja'
      ],
      'beneficios' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total de beneficios para el período de la caja'
      ],
      'venta_efectivo' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total vendido en efectivo'
      ],
      'venta_tarjetas' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total vendido mediante tarjetas'
      ],
      'efectivo_apertura' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total en efectivo en la caja al momento de la apertura'
      ],
      'efectivo_cierre' => [
        'type'    => Base::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total en efectivo en la caja al momento del cierre'
      ],
      '1c' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de un centimo'
      ],
      '2c' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de dos centimos'
      ],
      '5c' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de cinco centimos'
      ],
      '10c' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de diez centimos'
      ],
      '20c' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de veinte centimos'
      ],
      '50c' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de cincuenta centimos'
      ],
      '1e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de un euro'
      ],
      '2e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de dos euros'
      ],
      '5e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de cinco euros'
      ],
      '10e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de diez euros'
      ],
      '20e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de veinte euros'
      ],
      '50e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de cincuenta euros'
      ],
      '100e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de cien euros'
      ],
      '200e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de doscientos euros'
      ],
      '500e' => [
        'type'    => Base::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de quinientos euros'
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