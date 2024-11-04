<?php
use Osumi\OsumiFramework\App\Component\Model\PdfPedido\PdfPedidoComponent;

foreach ($list as $i => $pdfpedido) {
  $component = new PdfPedidoComponent([ 'pdfpedido' => $pdfpedido ]);
	echo strval($component);
	if ($i < count($list) - 1) {
		echo ",\n";
	}
}
