<?php if (is_null($vistapedido)): ?>
	null
<?php else: ?>
	{
	"idColumn": {{ vistapedido.id_column }},
	"status": {{ vistapedido.status | bool }}
	}
<?php endif ?>
