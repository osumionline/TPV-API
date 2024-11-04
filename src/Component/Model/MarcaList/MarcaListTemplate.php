<?php
use Osumi\OsumiFramework\App\Component\Model\Marca\MarcaComponent;

foreach ($list as $i => $marca) {
  $component = new MarcaComponent([ 'marca' => $marca ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
