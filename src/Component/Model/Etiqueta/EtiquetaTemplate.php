<?php if (is_null($etiqueta)): ?>
	null
<?php else: ?>
	{
	"id": {{ etiqueta.id }},
	"texto": {{ etiqueta.texto | string }},
	"slug": {{ etiqueta.slug | string }}
	}
<?php endif ?>
