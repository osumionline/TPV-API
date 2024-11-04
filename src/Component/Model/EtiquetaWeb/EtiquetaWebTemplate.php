<?php if (is_null($etiquetaweb)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $etiquetaweb->id ?>,
	"texto": <?php echo urlencode($etiquetaweb->texto) ?>,
	"slug": <?php echo urlencode($etiquetaweb->slug) ?>
	}
<?php endif ?>
