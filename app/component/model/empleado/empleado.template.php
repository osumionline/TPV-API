<?php if (is_null($values['empleado'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['empleado']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['empleado']->get('nombre')) ?>",
	"color": "<?php echo $values['empleado']->get('color') ?>"
}
<?php endif ?>