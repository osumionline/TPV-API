<?php
class Tarjeta extends OBase{
  function __construct(){
    $table_name = 'tarjeta';
    $model = [
        'id' => [
          'type'    => Base::PK,
          'comment' => 'Id único de cada tarjeta'
        ],
        'nombre' => [
          'type'    => Base::TEXT,
          'size'    => 50,
          'comment' => 'Nombre de la tarjeta'
        ],
        'abreviatura' => [
          'type'    => Base::TEXT,
          'size'    => 10,
          'comment' => 'Abreviatura del nombre de la tarjeta'
        ],
        'id_logo' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la imagen usada como logo de la tarjeta'
        ],
        'id_icono' => [
          'type'    => Base::NUM,
          'comment' => 'Id de la imagen usada como icono de la tarjeta'
        ],
        'comision' => [
          'type'    => Base::FLOAT,
          'comment' => 'Porcentaje de comisión que se cobra en cada venta'
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