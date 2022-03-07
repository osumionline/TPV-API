<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $cliente) {
	echo OTools::getComponent('model/cliente', [ 'cliente' => $cliente ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
