<?php
use Osumi\OsumiFramework\App\Component\Model\LineaVenta\LineaVentaComponent;

foreach ($list as $i => $lineaventa) {
  $component = new LineaVentaComponent([ 'lineaventa' => $lineaventa ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
