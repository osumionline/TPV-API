<?php
use OsumiFramework\App\Component\Model\ReservaComponent;

foreach ($values['list'] as $i => $reserva) {
  $component = new ReservaComponent([ 'reserva' => $reserva ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
