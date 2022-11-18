<?php
use OsumiFramework\App\Component\Model\PagocajaComponent;

foreach ($values['list'] as $i => $pagocaja) {
  $component = new PagocajaComponent([ 'pagocaja' => $pagocaja ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
