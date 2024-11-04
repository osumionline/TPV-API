<?php

use Osumi\OsumiFramework\App\Component\Model\LineaPedidoList\LineaPedidoListComponent;
use Osumi\OsumiFramework\App\Component\Model\PdfPedidoList\PdfPedidoListComponent;
use Osumi\OsumiFramework\App\Component\Model\VistaPedidoList\VistaPedidoListComponent;
?>
<?php if (is_null($pedido)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $pedido->id ?>,
	"idProveedor": <?php echo is_null($pedido->id_proveedor) ? 'null' : $pedido->id_proveedor ?>,
	"idMetodoPago": <?php echo is_null($pedido->metodo_pago) ? 'null' : $pedido->metodo_pago ?>,
	"metodoPago": <?php echo is_null($pedido->getMetodoPago()) ? 'null' : '"' . urlencode($pedido->getMetodoPago()) . '"' ?>,
	"tipo": <?php echo is_null($pedido->tipo) ? 'null' : '"' . urlencode($pedido->tipo) . '"' ?>,
	"num": <?php echo is_null($pedido->num) ? 'null' : '"' . urlencode($pedido->num) . '"' ?>,
	"importe": <?php echo $pedido->importe ?>,
	"portes": <?php echo $pedido->portes ?>,
	"descuento": <?php echo $pedido->descuento ?>,
	"fechaPago": "<?php echo is_null($pedido->fecha_pago) ? 'null' : $pedido->get('fecha_pago', 'd/m/Y') ?>",
	"fechaPedido": "<?php echo is_null($pedido->fecha_pedido) ? 'null' : $pedido->get('fecha_pedido', 'd/m/Y') ?>",
	"fechaRecepcionado": "<?php echo is_null($pedido->fecha_recepcionado) ? 'null' : $pedido->get('fecha_recepcionado', 'd/m/Y') ?>",
	"re": <?php echo $pedido->re ? 'true' : 'false' ?>,
	"ue": <?php echo $pedido->europeo ? 'true' : 'false' ?>,
	"faltas": <?php echo $pedido->faltas ? 'true' : 'false' ?>,
	"recepcionado": <?php echo $pedido->recepcionado ? 'true' : 'false' ?>,
	"observaciones": <?php echo is_null($pedido->observaciones) ? 'null' : '"' . urlencode($pedido->observaciones)) . '"' ?>,
	"lineas": [<?php echo new LineaPedidoListComponent(['list' => $pedido->getLineas()) ?>],
	"pdfs": [<?php echo new PdfPedidoListComponent(['list' => $pedido->getPdfs()]) ?>],
	"vista": [<?php echo new VistaPedidoListComponent(['list' => $pedido->getVista()]) ?>]
	}
<?php endif ?>
