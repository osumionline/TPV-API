<?php if (is_null($values['tipo_pago'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['tipo_pago']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['tipo_pago']->get('nombre')) ?>",
	"slug": "<?php echo urlencode($values['tipo_pago']->get('slug')) ?>",
	"afectaCaja": <?php echo $values['tipo_pago']->get('afecta_caja') ? 'true' : 'false' ?>,
	"orden": <?php echo $values['tipo_pago']->get('orden') ?>
}
<?php endif ?>
