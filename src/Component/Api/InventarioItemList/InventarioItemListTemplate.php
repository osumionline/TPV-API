<?php
use Osumi\OsumiFramework\App\Component\Api\InventarioItem\InventarioItemComponent;

foreach ($list as $i => $item) {
	$item_component = new InventarioItemComponent([ 'item' => $item ]);
	echo strval($item_component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
