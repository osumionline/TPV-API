<?php
use OsumiFramework\App\Component\Model\PdfPedidoComponent;

foreach ($values['list'] as $i => $pdfpedido) {
  $component = new PdfPedidoComponent([ 'pdf_pedido' => $pdfpedido ]);
	echo strval($component);
	if ($i<count($values['list'])-1) {
		echo ",\n";
	}
}
