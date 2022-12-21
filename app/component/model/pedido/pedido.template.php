<?php
use OsumiFramework\App\Component\Model\LineaPedidoListComponent;
use OsumiFramework\App\Component\Model\PdfPedidoListComponent;
use OsumiFramework\App\Component\Model\VistaPedidoListComponent;

if (is_null($values['pedido'])) {
?>
null
<?php
}
else {
?>
{
	"id": <?php echo $values['pedido']->get('id') ?>,
	"idProveedor": <?php echo is_null($values['pedido']->get('id_proveedor')) ? 'null' : '"'.$values['pedido']->get('id_proveedor').'"' ?>,
	"proveedor": <?php echo is_null($values['pedido']->getProveedor()) ? 'null' : '"'.urlencode($values['pedido']->getProveedor()->get('nombre')).'"' ?>,
	"tipo": "<?php echo $values['pedido']->get('tipo') ?>",
	"num": "<?php echo urlencode($values['pedido']->get('num')) ?>",
	"importe": <?php echo $values['pedido']->get('importe') ?>,
	"portes": <?php echo $values['pedido']->get('portes') ?>,
	"fechaPago": <?php echo is_null($values['pedido']->get('fecha_pago')) ? 'null' : '"'.$values['pedido']->get('fecha_pago', 'd/m/Y').'"' ?>,
	"fechaPedido": <?php echo is_null($values['pedido']->get('fecha_pedido')) ? 'null' : '"'.$values['pedido']->get('fecha_pedido', 'd/m/Y').'"' ?>,
	"fechaRecepcionado": <?php echo is_null($values['pedido']->get('fecha_recepcionado')) ? 'null' : '"'.$values['pedido']->get('fecha_recepcionado', 'd/m/Y').'"' ?>,
	"re": <?php echo $values['pedido']->get('re') ? 'true' : 'false' ?>,
	"ue": <?php echo $values['pedido']->get('europeo') ? 'true' : 'false' ?>,
	"faltas": <?php echo $values['pedido']->get('faltas') ? 'true' : 'false' ?>,
	"recepcionado": <?php echo $values['pedido']->get('recepcionado') ? 'true' : 'false' ?>,
	"observaciones": <?php echo is_null($values['pedido']->get('observaciones')) ? 'null' : '"'.urlencode($values['pedido']->get('observaciones')).'"' ?>,
	"lineas": [<?php echo new LineaPedidoListComponent(['list' => $values['pedido']->getLineas(), 'recepcionado' => $values['pedido']->get('recepcionado')]) ?>],
	"pdfs": [<?php echo new PdfPedidoListComponent(['list' => $values['pedido']->getPdfs()]) ?>],
	"vista": [<?php echo new VistaPedidoListComponent(['list' => $values['pedido']->getVista()]) ?>]
}
<?php
}
?>
