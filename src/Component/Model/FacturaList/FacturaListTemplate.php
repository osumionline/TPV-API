<?php
use Osumi\OsumiFramework\App\Component\Model\Factura\FacturaComponent;

foreach ($list as $i => $factura) {
  $component = new FacturaComponent([ 'factura' => $factura ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
