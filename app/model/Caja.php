<?php
class Caja extends OModel{
  function __construct(){
    $table_name  = 'caja';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id único para cada cierre de caja'
      ],
      'apertura' => [
        'type'    => OCore::DATE,
        'nullable' => false,
        'default' => null,
        'comment' => 'Fecha de apertura de la caja'
      ],
      'cierre' => [
        'type'    => OCore::DATE,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de cierre de la caja'
      ],
      'diferencia' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Diferencia entre el importe que debería haber y el que realmente hay'
      ],
      'ventas' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total de ventas para el período de la caja'
      ],
      'beneficios' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total de beneficios para el período de la caja'
      ],
      'venta_efectivo' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total vendido en efectivo'
      ],
      'venta_tarjetas' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total vendido mediante tarjetas'
      ],
      'efectivo_apertura' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total en efectivo en la caja al momento de la apertura'
      ],
      'efectivo_cierre' => [
        'type'    => OCore::FLOAT,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Importe total en efectivo en la caja al momento del cierre'
      ],
      '1c' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de un centimo'
      ],
      '2c' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de dos centimos'
      ],
      '5c' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de cinco centimos'
      ],
      '10c' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de diez centimos'
      ],
      '20c' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de veinte centimos'
      ],
      '50c' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de cincuenta centimos'
      ],
      '1e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de un euro'
      ],
      '2e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de monedas de dos euros'
      ],
      '5e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de cinco euros'
      ],
      '10e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de diez euros'
      ],
      '20e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de veinte euros'
      ],
      '50e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de cincuenta euros'
      ],
      '100e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de cien euros'
      ],
      '200e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de doscientos euros'
      ],
      '500e' => [
        'type'    => OCore::NUM,
        'nullable' => true,
        'default' => '0',
        'comment' => 'Cantidad de billetes de quinientos euros'
      ],
      'created_at' => [
        'type'    => OCore::CREATED,
        'comment' => 'Fecha de creación del registro'
      ],
      'updated_at' => [
        'type'    => OCore::UPDATED,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de última modificación del registro'
      ]
    ];

    parent::load($table_name, $model);
  }
}