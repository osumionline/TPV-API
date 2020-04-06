<?php
class articulosService extends OService{
  function __construct(){
    $this->loadService();
  }
  
  public function getMarcas(){
    $db = new ODB();
    $sql = "SELECT * FROM `marca` ORDER BY `nombre`";
    $db->query($sql);
    $list = [];
    
    while ($res=$db->next()){
      $marca = new Marca();
      $marca->update($res);
      array_push($list, $marca);
    }
    
    return $list;
  }
  
  public function getProveedores(){
    $db = new ODB();
    $sql = "SELECT * FROM `proveedor` ORDER BY `nombre`";
    $db->query($sql);
    $list = [];
    
    while ($res=$db->next()){
      $proveedor = new Proveedor();
      $proveedor->update($res);
      array_push($list, $proveedor);
    }
    
    return $list;
  }
  
  public function updateProveedoresMarcas($id_proveedor, $marcas){
    $db = new ODB();
    $sql = "DELETE FROM `proveedor_marca` WHERE `id_proveedor` = ?";
    $db->query($sql, [$id_proveedor]);
    
    foreach ($marcas as $id_marca){
      $pm = new ProveedorMarca();
      $pm->set('id_proveedor', $id_proveedor);
      $pm->set('id_marca',     $id_marca);
      $pm->save();
    }
  }
  
  public function getCategories($id_parent){
    $db = new ODB();
    $sql = "SELECT * FROM `categoria`";
    if ($id_parent!==-1){
      $sql .= " WHERE `id_padre` ".( is_null($id_parent) ? "IS NULL" : "= ".$id_parent );
    }

    $ret = [];
    $db->query($sql);

    while ($res=$db->next()){
      $cat = new Categoria();
      $cat->update($res);

      array_push($ret, $cat);
    }

    return $ret;
  }
  
  public function getCategoryTree($options){
    $options['id_category'] = array_key_exists('id_category', $options) ? $options['id_category'] : 0;
    $options['depth'] = array_key_exists('depth', $options) ? $options['depth'] : 0;

    $cat = new Categoria();
    $options['depth']++;
    if ($options['id_category']==0){
      $cat->set('id',       0);
      $cat->set('nombre',   'Inicio');
      $cat->set('id_padre', null);
      $options['depth'] = 0;
    }
    else{
      $cat->find(['id'=>$options['id_category']]);
    }

    $item = [
      'id'          => $cat->get('id'),
      'nombre'      => $cat->get('nombre'),
      'profundidad' => $options['depth'],
      'hijos'    => []
    ];

    $children = $this->getCategories($options['id_category']);

    foreach($children as $child){
      $new_options = [
        'id_category' => $child->get('id'),
        'depth'       => $options['depth']
      ];
      array_push($item['hijos'], $this->getCategoryTree($new_options));
    }

    return $item;
  }
  
  public function getNewLocalizador(){
    $loc = date('y', time()) . str_pad(rand(1, 9999), 4, STR_PAD_LEFT);
    $art = new Articulo();
    
    if ($art->check(['localizador'=>$loc])){
      return $this->getNewLocalizador();
    }
    else{
      return $loc;
    }
  }
}