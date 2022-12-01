<?php if (is_null($values['pedido'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['pedido']->get('id') ?>,
	"idProveedor": <?php echo $values['pedido']->get('id_proveedor') ?>,
	"proveedor": <?php echo is_null($values['pedido']->getProveedor()) ? 'null' : '"'.urlencode($values['pedido']->getProveedor()).'"' ?>,
	"albaranFactura": <?php echo $values['pedido']->get('albaran_factura') ? 'true' : 'false' ?>,
	"numAlbaranFactura": "<?php echo urlencode($values['pedido']->get('num_albaran_factura')) ?>",
	"importe": <?php echo $values['pedido']->get('importe') ?>,
	"portes": <?php echo $values['pedido']->get('portes') ?>,
	"fechaPago": "<?php echo is_null($values['pedido']->get('fecha_pago')) ? 'null' : $values['pedido']->get('fecha_pago', 'd/m/Y H:i:s') ?>",
	"fechaPedido": "<?php echo is_null($values['pedido']->get('fecha_pedido')) ? 'null' : $values['pedido']->get('fecha_pedido', 'd/m/Y H:i:s') ?>",
	"re": <?php echo $values['pedido']->get('re') ? 'true' : 'false' ?>,
	"ue": <?php echo $values['pedido']->get('europeo') ? 'true' : 'false' ?>,
	"faltas": <?php echo $values['pedido']->get('faltas') ? 'true' : 'false' ?>,
	"recepcionado": <?php echo $values['pedido']->get('recepcionado') ? 'true' : 'false' ?>
}
<?php endif ?>