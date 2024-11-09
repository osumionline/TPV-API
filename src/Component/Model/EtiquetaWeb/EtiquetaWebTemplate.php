<?php if (is_null($etiquetaweb)): ?>
	null
<?php else: ?>
	{
	"id": {{ etiquetaweb.id }},
	"texto": {{ etiquetaweb.texto | string }},
	"slug": {{ etiquetaweb.slug | string }}
	}
<?php endif ?>
