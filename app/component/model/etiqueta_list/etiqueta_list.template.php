<?php
use OsumiFramework\App\Component\Model\EtiquetaComponent;

foreach ($values['list'] as $i => $etiqueta) {
  $component = new EtiquetaComponent([ 'etiqueta' => $etiqueta ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
