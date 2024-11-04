<?php
use Osumi\OsumiFramework\App\Component\Model\Comercial\ComercialComponent;

foreach ($list as $i => $comercial) {
  $component = new ComercialComponent([ 'comercial' => $comercial ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
