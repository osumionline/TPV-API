<?php
use OsumiFramework\App\Component\Model\ComercialComponent;

foreach ($values['list'] as $i => $comercial) {
  $comercial_component = new ComercialComponent([ 'comercial' => $comercial ]);
	echo strval($comercial_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
