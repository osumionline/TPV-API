<?php if (is_null($pagocaja)): ?>
null
<?php else: ?>
{
	"id": <?php echo $pagocaja->id ?>,
	"concepto": "<?php echo urlencode($pagocaja->concepto) ?>",
	"descripcion": <?php echo is_null($pagocaja->descripcion) ? 'null' : '"'.urlencode($pagocaja->descripcion).'"' ?>,
	"importe": <?php echo $pagocaja->importe ?>,
	"fecha": "<?php echo $pagocaja->get('created_at', 'd/m/Y H:i') ?>",
	"editable": <?php echo $pagocaja->getEditable() ? 'true' : 'false' ?>
}
<?php endif ?>
