<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $tipopago) {
	echo OTools::getComponent('model/tipo_pago', [ 'tipo_pago' => $tipopago ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
