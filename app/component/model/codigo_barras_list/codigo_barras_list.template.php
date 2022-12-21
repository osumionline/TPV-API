<?php
use OsumiFramework\App\Component\Model\CodigoBarrasComponent;

foreach ($values['list'] as $i => $codigo_barras) {
  $component = new CodigoBarrasComponent([ 'codigo_barras' => $codigo_barras ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
