<?php if (is_null($empleado)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $empleado->id ?>,
	"nombre": "<?php echo urlencode($empleado->nombre) ?>",
	"hasPassword": <?php echo !is_null($empleado->pass) ? 'true' : 'false' ?>,
	"color": "<?php echo urlencode($empleado->color) ?>",
	"roles": [<?php echo implode(', ', $empleado->getRoles()) ?>]
	}
<?php endif ?>
