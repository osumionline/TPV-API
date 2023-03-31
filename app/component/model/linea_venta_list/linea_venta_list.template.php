<?php
use OsumiFramework\App\Component\Model\LineaVentaComponent;

foreach ($values['list'] as $i => $linea_venta) {
	$component = new LineaVentaComponent([ 'linea_venta' => $linea_venta ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
