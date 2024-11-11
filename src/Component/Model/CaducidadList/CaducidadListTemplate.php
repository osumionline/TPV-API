<?php
use Osumi\OsumiFramework\App\Component\Model\Caducidad\CaducidadComponent;

foreach ($list as $i => $caducidad) {
  $component = new CaducidadComponent([ 'caducidad' => $caducidad ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
