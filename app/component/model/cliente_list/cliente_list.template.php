<?php
use OsumiFramework\App\Component\ClienteComponent;

foreach ($values['list'] as $i => $cliente) {
	$cliente_component = new ClienteComponent([ 'cliente' => $cliente ]);
	echo strval($cliente_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
