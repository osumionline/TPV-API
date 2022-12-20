<?php if (is_null($values['vista_pedido'])): ?>
null
<?php else: ?>
{
	"idColumn": <?php echo $values['vista_pedido']->get('id_column') ?>,
	"status": <?php echo $values['vista_pedido']->get('status') ? 'true' : 'false' ?>
}
<?php endif ?>
