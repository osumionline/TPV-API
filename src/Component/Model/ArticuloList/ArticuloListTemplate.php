<?php
use Osumi\OsumiFramework\App\Component\Model\Articulo\ArticuloComponent;

foreach ($list as $i => $articulo) {
  $component = new ArticuloComponent([ 'articulo' => $articulo ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
