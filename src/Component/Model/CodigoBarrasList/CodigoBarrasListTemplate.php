<?php
use Osumi\OsumiFramework\App\Component\Model\CodigoBarras\CodigoBarrasComponent;

foreach ($list as $i => $codigobarras) {
  $component = new CodigoBarrasComponent([ 'codigobarras' => $codigobarras ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
