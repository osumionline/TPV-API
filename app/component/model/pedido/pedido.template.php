<?php if (is_null($values['pedido'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['pedido']->get('id') ?>,
	"idProveedor": <?php echo $values['pedido']->get('id_proveedor') ?>,
	"proveedor": <?php echo is_null($values['pedido']->getProveedor()) ? 'null' : '"'.urlencode($values['pedido']->getProveedor()).'"' ?>,
	"albaran": "<?php echo urlencode($values['pedido']->get('albaran')) ?>",
	"importe": <?php echo $values['pedido']->get('importe') ?>,
	"recepcionado": <?php echo $values['pedido']->get('recepcionado') ? 'true' : 'false' ?>,
	"fecha": "<?php echo $values['pedido']->get('created_at', 'd/m/Y H:i') ?>"
}
<?php endif ?>
