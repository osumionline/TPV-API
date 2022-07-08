<?php
use OsumiFramework\App\Component\Model\ProveedorComponent;

foreach ($values['list'] as $i => $proveedor) {
	$proveedor_component = new ProveedorComponent([ 'proveedor' => $proveedor ]);
	echo strval($proveedor_component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
