<?php if (is_null($pdfpedido)): ?>
	null
<?php else: ?>
	{
	"id": {{ pdfpedido.id }},
	"data": null,
	"nombre": {{ pdfpedido.nombre | string }},
	"url": "<?php echo $pdfpedido->getUrl() ?>",
	"deleted": false
	}
<?php endif ?>
