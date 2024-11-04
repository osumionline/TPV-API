<?php
use Osumi\OsumiFramework\App\Component\Model\LineaReserva\LineaReservaComponent;

foreach ($list as $i => $lineareserva) {
  $component = new LineaReservaComponent([ 'lineareserva' => $lineareserva ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
