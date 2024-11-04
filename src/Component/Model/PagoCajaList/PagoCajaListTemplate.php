<?php
use Osumi\OsumiFramework\App\Component\Model\PagoCaja\PagoCajaComponent;

foreach ($list as $i => $pagocaja) {
  $component = new PagoCajaComponent([ 'pagocaja' => $pagocaja ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
