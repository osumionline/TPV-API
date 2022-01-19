<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $tarjeta) {
	echo OTools::getComponent('model/tarjeta', [ 'tarjeta' => $tarjeta ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
