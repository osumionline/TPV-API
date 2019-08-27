<?php
class ProveedorMarca extends OBase{
  function __construct(){
    $table_name  = 'proveedor_marca';
    $model = [
      'id_proveedor' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'proveedor.id',
        'comment' => 'Id del proveedor'
      ],
      'id_marca' => [
        'type'    => Base::PK,
        'incr' => false,
        'ref' => 'marca.id',
        'comment' => 'Id de la marca'
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