<?php
class api extends OController{
  /*
   * Función llamada por API
   */
  public function apiCall($req){
    $status = 'ok';
    $this->getTemplate()->add('status',$status);
  }
}