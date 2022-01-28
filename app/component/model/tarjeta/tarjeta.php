<?php if (is_null($values['tarjeta'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['tarjeta']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['tarjeta']->get('nombre')) ?>",
	"slug": "<?php echo urlencode($values['tarjeta']->get('slug')) ?>",
	"comision": <?php echo $values['tarjeta']->get('comision') ?>,
	"porDefecto": <?php echo $values['tarjeta']->get('por_defecto') ? 'true' : 'false' ?>
}
<?php endif ?>
