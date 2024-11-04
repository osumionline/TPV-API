<?php
use Osumi\OsumiFramework\App\Component\Model\Reserva\ReservaComponent;

foreach ($list as $i => $reserva) {
  $component = new ReservaComponent([ 'reserva' => $reserva ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
