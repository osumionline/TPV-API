<?php if (is_null($etiqueta)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $etiqueta->id ?>,
	"texto": <?php echo urlencode($etiqueta->texto) ?>,
	"slug": <?php echo urlencode($etiqueta->slug) ?>
	}
<?php endif ?>
