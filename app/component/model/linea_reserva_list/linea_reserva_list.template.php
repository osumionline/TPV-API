<?php
use OsumiFramework\App\Component\Model\LineaReservaComponent;

foreach ($values['list'] as $i => $linea_reserva) {
  $component = new LineaReservaComponent([ 'linea_reserva' => $linea_reserva ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
