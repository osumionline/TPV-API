<?php if (is_null($codigobarras)): ?>
	null
<?php else: ?>
	{
	"id": {{ codigobarras.id }},
	"codigoBarras": {{ codigobarras.codigo_barras | string }},
	"porDefecto": {{ codigobarras.por_defecto | bool }}
	}
<?php endif ?>
