<?php
use OsumiFramework\App\Component\Model\VentaComponent;

foreach ($values['list'] as $i => $venta) {
  $component = new VentaComponent([ 'venta' => $venta ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
