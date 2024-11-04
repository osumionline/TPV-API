<?php
use Osumi\OsumiFramework\App\Component\Model\EtiquetaWeb\EtiquetaWebComponent;

foreach ($list as $i => $etiquetaweb) {
  $component = new EtiquetaWebComponent([ 'etiquetaweb' => $etiquetaweb ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
