<?php
use OsumiFramework\App\Component\Model\EtiquetaWebComponent;

foreach ($values['list'] as $i => $etiqueta_web) {
  $component = new EtiquetaWebComponent([ 'etiqueta_web' => $etiqueta_web ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
