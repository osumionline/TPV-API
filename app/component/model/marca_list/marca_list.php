<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $marca) {
	echo OTools::getComponent('model/marca', [ 'marca' => $marca ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
