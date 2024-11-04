<?php
use Osumi\OsumiFramework\App\Component\Model\Etiqueta\EtiquetaComponent;

foreach ($list as $i => $etiqueta) {
  $component = new EtiquetaComponent([ 'etiqueta' => $etiqueta ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
