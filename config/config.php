<?php
  /* Datos generales */
  date_default_timezone_set('Europe/Madrid');

  $basedir = realpath(dirname(__FILE__));
  $basedir = str_ireplace('config','',$basedir);

  require($basedir.'model/base/OConfig.php');
  $c = new OConfig();
  $c->setBaseDir($basedir);

  /* Carga de módulos */
  $c->loadDefaultModules();

  /* Carga de paquetes */
  //$c->loadPackages();

  /* Datos de la Base De Datos */
  $c->setDB('host','localhost');
  $c->setDB('user','apitpv');
  $c->setDB('pass','Uhn94&9j');
  $c->setDB('name','apitpv');

  /* Datos para cookies */
  $c->setCookiePrefix('osumitpv');
  $c->setCookieUrl('.osumi.es');
  
  /* Activa/desactiva el modo debug que guarda en log las consultas SQL e información variada */
  $c->setDebugMode(false);

  /* URL del sitio */
  $c->setBaseUrl('http://apitpv.osumi.es/');
  
  /* Email del administrador al que se notificarán varios eventos */
  $c->setAdminEmail('inigo.gorosabel@osumi.es');
  
  /* Lista de CSS por defecto */
  $c->setCssList( array() );
  
  /* Lista de JavaScript por defecto */
  $c->setJsList( array() );
  
  /* Título de la página */
  $c->setDefaultTitle('Osumi TPV');

  /* Idioma de la página */
  $c->setLang('es');
  
  /* Para cerrar la página descomentar la siguiente linea */
  //$c->setPaginaCerrada(true);
  
  /* Páginas de error customizadas */
  //$c->setErrorPage('403','/admin');

  /* Backend */
  //$c->setBackend('user','admin');
  //$c->setBackend('pass','cb5ef71ffc7a67bdb217c5496d3a36d5be0b5d25');