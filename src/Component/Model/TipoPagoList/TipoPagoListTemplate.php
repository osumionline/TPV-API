<?php
use Osumi\OsumiFramework\App\Component\Model\TipoPago\TipoPagoComponent;

foreach ($list as $i => $tipopago) {
  $component = new TipoPagoComponent([ 'tipopago' => $tipopago ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
