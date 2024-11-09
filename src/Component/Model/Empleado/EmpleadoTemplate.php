<?php if (is_null($empleado)): ?>
	null
<?php else: ?>
	{
	"id": {{ empleado.id }},
	"nombre": {{ empleado.nombre | string }},
	"hasPassword": <?php echo !is_null($empleado->pass) ? 'true' : 'false' ?>,
	"color": {{ empleado.color | string }},
	"roles": [<?php echo implode(', ', $empleado->getRoles()) ?>]
	}
<?php endif ?>
