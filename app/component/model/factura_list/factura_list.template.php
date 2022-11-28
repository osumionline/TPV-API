<?php
use OsumiFramework\App\Component\Model\FacturaComponent;

foreach ($values['list'] as $i => $factura) {
  $component = new FacturaComponent([ 'factura' => $factura ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
