<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $proveedor) {
	echo OTools::getComponent('model/proveedor', [ 'proveedor' => $proveedor ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
