<?php
use Osumi\OsumiFramework\App\Component\Model\Proveedor\ProveedorComponent;

foreach ($list as $i => $proveedor) {
  $component = new ProveedorComponent([ 'proveedor' => $proveedor ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
