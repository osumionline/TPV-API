<?php
use Osumi\OsumiFramework\App\Component\Model\VistaPedido\VistaPedidoComponent;

foreach ($list as $i => $vistapedido) {
  $component = new VistaPedidoComponent([ 'vistapedido' => $vistapedido ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
