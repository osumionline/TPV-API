<?php if (is_null($values['pdf_pedido'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['pdf_pedido']->get('id') ?>,
	"data": null,
	"nombre": "<?php echo is_null($values['pdf_pedido']->get('nombre')) ? 'null' : urlencode($values['pdf_pedido']->get('nombre')) ?>",
	"url": "<?php echo $values['pdf_pedido']->getUrl() ?>",
	"deleted": false
}
<?php endif ?>
