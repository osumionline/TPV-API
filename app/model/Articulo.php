<?php
class Articulo extends OModel{
  function __construct(){
    $table_name  = 'articulo';
    $model = [
      'id' => [
        'type'    => OCore::PK,
        'comment' => 'Id único de cada artículo'
      ],
      'localizador' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Localizador único de cada artículo'
      ],
      'nombre' => [
        'type'    => OCore::TEXT,
        'nullable' => false,
        'default' => null,
        'size' => 100,
        'comment' => 'Nombre del artículo'
      ],
      'puc' => [
        'type'    => OCore::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Precio Unitario de Compra del artículo'
      ],
      'pvp' => [
        'type'    => OCore::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Precio de Venta al Público del artículo'
      ],
      'margen' => [
        'type'    => OCore::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Margen de beneficio del artículo'
      ],
      'palb' => [
        'type'    => OCore::FLOAT,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Precio del artículo en el albarán'
      ],
      'id_marca' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'marca.id',
        'comment' => 'Id de la marca del artículo'
      ],
      'id_proveedor' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'ref' => 'proveedor.id',
        'comment' => 'Id del proveedor del artículo'
      ],
      'stock' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Stock actual del artículo'
      ],
      'stock_min' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Stock mínimo del artículo'
      ],
      'stock_max' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Stock máximo del artículo'
      ],
      'lote_optimo' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => '0',
        'comment' => 'Lote óptimo para realizar pedidos del artículo'
      ],
      'iva' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'IVA del artículo'
      ],
      'fecha_caducidad' => [
        'type'    => OCore::DATE,
        'nullable' => true,
        'default' => null,
        'comment' => 'Fecha de caducidad del artículo'
      ],
      'mostrar_feccad' => [
        'type'    => OCore::BOOL,
        'comment' => 'Mostrar fecha de caducidad 0 no 1 si'
      ],
      'observaciones' => [
        'type'    => OCore::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Observaciones o notas sobre el artículo'
      ],
      'mostrar_obs_pedidos' => [
        'type'    => OCore::BOOL,
        'comment' => 'Mostrar observaciones en pedidos 0 no 1 si'
      ],
      'mostrar_obs_ventas' => [
        'type'    => OCore::BOOL,
        'comment' => 'Mostrar observaciones en ventas 0 no 1 si'
      ],
      'referencia' => [
        'type'    => OCore::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 50,
        'comment' => 'Referencia original del proveedor'
      ],
      'venta_online' => [
        'type'    => OCore::BOOL,
        'comment' => 'Indica si el producto está disponible desde la web 1 o no 0'
      ],
      'mostrar_en_web' => [
        'type'    => OCore::BOOL,
        'comment' => 'Indica si debe ser mostrado en la web 1 o no 0'
      ],
      'id_categoria' => [
        'type'    => OCore::NUM,
        'nullable' => false,
        'default' => null,
        'comment' => 'Id de la categoría en la que se engloba el artículo'
      ],
      'desc_corta' => [
        'type'    => OCore::TEXT,
        'nullable' => true,
        'default' => null,
        'size' => 250,
        'comment' => 'Descripción corta para la web'
      ],
      'desc' => [
        'type'    => OCore::LONGTEXT,
        'nullable' => true,
        'default' => null,
        'comment' => 'Descripción larga para la web'
      ],
      'activo' => [
        'type'    => OCore::BOOL,
        'default' => true,
        'comment' => 'Indica si el artículo está en alta 1 o dado de baja 0'
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
  
  private $codigos_barras = null;
  
  public function getCodigosBarras(){
    if (is_null($this->codigos_barras)){
      $this->loadCodigosBarras();
    }
    return $this->codigos_barras;
  }
  
  public function setCodigosBarras($cb){
    $this->codigos_barras = $cb;
  }
  
  public function loadCodigosBarras(){
    $db = new ODB();
    $sql = "SELECT * FROM `codigo_barras` WHERE `id_articulo` = ? ORDER BY `por_defecto` DESC, `created_at` ASC";
    $db->query($sql, [$this->get('id')]);
    $list = [];
    
    while ($res=$db->next()){
      $cb = new CodigoBarras();
      $cb->update($res);
      array_push($list, $cb);
    }

    $this->setCodigosBarras($list);
  }
}