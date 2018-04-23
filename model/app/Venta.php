<?php
class Venta extends OBase{
  function __construct(){
    $model_name = get_class($this);
    $tablename  = 'venta';
    $model = array(
        'id'         => array('type'=>Base::PK,      'com'=>'Id único de cada venta'),
        'id_cliente' => array('type'=>Base::NUM,     'com'=>'Id del cliente en caso de que sea una venta a un cliente fichado'),
        'total'      => array('type'=>Base::NUM,     'com'=>'Importe total de la venta'),
        'entregado'  => array('type'=>Base::FLOAT,   'com'=>'Importe entregado por el cliente'),
        'tipo_pago'  => array('type'=>Base::NUM,     'com'=>'Tipo de pago 0 metálico 1 tarjeta 2 vale 3 web'),
        'id_tarjeta' => array('type'=>Base::NUM,     'com'=>'Id del tipo de tarjeta usada en el pago'),
        'saldo'      => array('type'=>Base::FLOAT,   'com'=>'Saldo en caso de que el ticket sea un vale'),
        'created_at' => array('type'=>Base::CREATED, 'com'=>'Fecha de creación del registro'),
        'updated_at' => array('type'=>Base::UPDATED, 'com'=>'Fecha de última modificación del registro')
    );

    parent::load($model_name,$tablename,$model);
  }
}