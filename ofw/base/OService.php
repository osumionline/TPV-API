<?php
  class OService{
    protected $controller = null;

    public final function setController($controller=null){
      if (is_null($controller)){
        $controller = new OController();
        $controller->blankController();
        
      }
      $this->controller = $controller;
    }

    public final function getController(){
      return $this->controller;
    }
  }
