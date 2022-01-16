<?php
use OsumiFramework\OFW\Tools\OTools;

foreach ($values['list'] as $i => $articulo) {
	echo OTools::getComponent('model/articulo', [ 'articulo' => $articulo ]);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
