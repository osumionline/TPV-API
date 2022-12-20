<?php
use OsumiFramework\App\Component\Model\VistaPedidoComponent;

foreach ($values['list'] as $i => $vista_pedido) {
  $component = new VistaPedidoComponent([ 'vista_pedido' => $vista_pedido ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
