<?php
use OsumiFramework\App\Component\Api\StatusIdMessageComponent;

foreach ($values['list'] as $i => $status) {
	$status_component = new StatusIdMessageComponent([ 'status' => $status ]);
	echo strval($status_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
