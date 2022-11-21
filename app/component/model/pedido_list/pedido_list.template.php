<?php
use OsumiFramework\App\Component\Model\PedidoComponent;

foreach ($values['list'] as $i => $pedido) {
  $component = new PedidoComponent([ 'pedido' => $pedido ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
