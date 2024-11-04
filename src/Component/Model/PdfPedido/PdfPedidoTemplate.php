<?php if (is_null($pdfpedido)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $pdfpedido->id ?>,
	"data": null,
	"nombre": <?php echo is_null($pdfpedido->nombre) ? 'null' : '"' . urlencode($pdfpedido->nombre) . '"' ?>,
	"url": "<?php echo $pdfpedido->getUrl() ?>",
	"deleted": false
	}
<?php endif ?>
