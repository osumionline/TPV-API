<?php
use Osumi\OsumiFramework\App\Component\Model\LineaPedido\LineaPedidoComponent;

foreach ($list as $i => $lineapedido) {
  $component = new LineaPedidoComponent([ 'lineapedido' => $lineapedido ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
