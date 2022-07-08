<?php
use OsumiFramework\App\Component\Model\MarcaComponent;

foreach ($values['list'] as $i => $marca) {
	$marca_component = new MarcaComponent([ 'marca' => $marca ]);
	echo strval($marca_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
