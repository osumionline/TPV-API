<?php
use OsumiFramework\App\Component\Api\InformeMensualItemComponent;

foreach ($values['list'] as $i => $item) {
	$item_component = new InformeMensualItemComponent([ 'item' => $item ]);
	echo strval($item_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
