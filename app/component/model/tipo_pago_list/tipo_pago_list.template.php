<?php
use OsumiFramework\App\Component\Model\TipoPagoComponent;

foreach ($values['list'] as $i => $tipopago) {
	$tipo_pago_component = new TipoPagoComponent([ 'tipo_pago' => $tipopago ]);
	echo strval($tipo_pago_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
