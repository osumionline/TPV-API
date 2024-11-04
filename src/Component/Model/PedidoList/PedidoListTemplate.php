<?php
use Osumi\OsumiFramework\App\Component\Model\Pedido\PedidoComponent;

foreach ($list as $i => $pedido) {
  $component = new PedidoComponent([ 'pedido' => $pedido ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
