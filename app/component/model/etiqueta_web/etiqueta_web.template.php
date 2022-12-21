<?php if (is_null($values['etiqueta_web'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['etiqueta_web']->get('id') ?>,
	"texto": "<?php echo urlencode($values['etiqueta_web']->get('texto')) ?>",
	"slug": "<?php echo urlencode($values['etiqueta_web']->get('slug')) ?>"
}
<?php endif ?>
