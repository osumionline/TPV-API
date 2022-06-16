<?php
use OsumiFramework\App\Component\ArticuloComponent;

foreach ($values['list'] as $i => $articulo) {
	$articulo_component = new ArticuloComponent([ 'articulo' => $articulo ]);
	echo strval($articulo_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
