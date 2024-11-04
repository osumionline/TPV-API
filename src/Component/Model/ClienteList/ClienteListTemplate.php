<?php
use Osumi\OsumiFramework\App\Component\Model\Cliente\ClienteComponent;

foreach ($list as $i => $cliente) {
  $component = new ClienteComponent([ 'cliente' => $cliente ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
