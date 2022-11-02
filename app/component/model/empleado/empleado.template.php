<?php if (is_null($values['empleado'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['empleado']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['empleado']->get('nombre')) ?>",
	"hasPassword": <?php echo !is_null($values['empleado']->get('password')) ? 'true' : 'false' ?>,
	"color": "#<?php echo $values['empleado']->get('color') ?>",
	"roles": [<?php echo implode(', ', $values['empleado']->getRoles()) ?>]
}
<?php endif ?>
