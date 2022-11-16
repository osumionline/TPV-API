<?php
use OsumiFramework\App\Component\Model\LineaventaComponent;

foreach ($values['list'] as $i => $lineaventa) {
  $component = new LineaventaComponent([ 'lineaventa' => $lineaventa ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
