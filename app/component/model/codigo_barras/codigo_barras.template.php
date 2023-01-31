<?php if (is_null($values['codigo_barras'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['codigo_barras']->get('id') ?>,
	"codigoBarras": "<?php echo $values['codigo_barras']->get('codigo_barras') ?>",
	"porDefecto": <?php echo $values['codigo_barras']->get('por_defecto') ? 'true' : 'false' ?>
}
<?php endif ?>
