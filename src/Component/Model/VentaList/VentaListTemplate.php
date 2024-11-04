<?php
use Osumi\OsumiFramework\App\Component\Model\Venta\VentaComponent;

foreach ($list as $i => $venta) {
  $component = new VentaComponent([ 'venta' => $venta ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
