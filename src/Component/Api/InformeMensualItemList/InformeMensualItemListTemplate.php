<?php
use Osumi\OsumiFramework\App\Component\Api\InformeMensualItem\InformeMensualItemComponent;

foreach ($list as $i => $item) {
	$item_component = new InformeMensualItemComponent([ 'item' => $item ]);
	echo strval($item_component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
