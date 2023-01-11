<?php
use OsumiFramework\App\Component\Api\InventarioItemComponent;

foreach ($values['list'] as $i => $item) {
	$item_component = new InventarioItemComponent([ 'item' => $item ]);
	echo strval($item_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
