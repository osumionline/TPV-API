<?php
use Osumi\OsumiFramework\App\Component\Api\StatusIdMessage\StatusIdMessageComponent;

foreach ($list as $i => $status) {
	$status_component = new StatusIdMessageComponent([ 'status' => $status ]);
	echo strval($status_component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
