<?php
use OsumiFramework\App\Component\Model\LineaPedidoComponent;

foreach ($values['list'] as $i => $linea_pedido) {
  $component = new LineaPedidoComponent([ 'linea_pedido' => $linea_pedido ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
