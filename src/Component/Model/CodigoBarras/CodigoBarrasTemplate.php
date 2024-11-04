<?php if (is_null($codigobarras)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $codigobarras->id ?>,
	"codigoBarras": "<?php echo urlencode($codigobarras->codigo_barras) ?>",
	"porDefecto": <?php echo $codigobarras->por_defecto ? 'true' : 'false' ?>
	}
<?php endif ?>
