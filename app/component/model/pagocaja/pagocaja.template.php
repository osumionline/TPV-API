<?php if (is_null($values['pagocaja'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['pagocaja']->get('id') ?>,
	"concepto": "<?php echo urlencode($values['pagocaja']->get('concepto')) ?>",
	"descripcion": "<?php echo is_null($values['pagocaja']->get('descripcion')) ? 'null' : urlencode($values['pagocaja']->get('descripcion')) ?>",
	"importe": <?php echo $values['pagocaja']->get('importe') ?>,
	"fecha": "<?php echo $values['pagocaja']->get('created_at', 'd/m/Y H:i') ?>",
	"editable": <?php echo $values['pagocaja']->getEditable() ? 'true' : 'false' ?>
}
<?php endif ?>
