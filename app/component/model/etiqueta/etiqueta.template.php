<?php if (is_null($values['etiqueta'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['etiqueta']->get('id') ?>,
	"texto": "<?php echo urlencode($values['etiqueta']->get('texto')) ?>",
	"slug": "<?php echo urlencode($values['etiqueta']->get('slug')) ?>"
}
<?php endif ?>
