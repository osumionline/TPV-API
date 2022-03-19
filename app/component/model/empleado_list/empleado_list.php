<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $empleado) {
	echo OTools::getComponent('model/empleado', [ 'empleado' => $empleado ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
