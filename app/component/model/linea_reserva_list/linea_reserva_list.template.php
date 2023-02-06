<?php
use OsumiFramework\App\Component\Model\LineaReservaComponent;

foreach ($values['list'] as $i => $lineareserva) {
  $component = new LineaReservaComponent([ 'lineareserva' => $lineareserva ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
