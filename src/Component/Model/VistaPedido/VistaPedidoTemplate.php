<?php if (is_null($vistapedido)): ?>
	null
<?php else: ?>
	{
	"idColumn": <?php echo $vistapedido->id_column ?>,
	"status": <?php echo $vistapedido->status ? 'true' : 'false' ?>
	}
<?php endif ?>
