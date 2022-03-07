<?php if (is_null($values['marca'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['marca']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['marca']->get('nombre')) ?>",
	"idFoto": <?php echo is_null($values['marca']->get('id_foto')) ? 'null' : $values['marca']->get('id_foto') ?>,
	"direccion": "<?php echo is_null($values['marca']->get('direccion')) ? 'null' : urlencode($values['marca']->get('direccion')) ?>",
	"telefono": "<?php echo is_null($values['marca']->get('telefono')) ? 'null' : urlencode($values['marca']->get('telefono')) ?>",
	"email": "<?php echo is_null($values['marca']->get('email')) ? 'null' : urlencode($values['marca']->get('email')) ?>",
	"web": "<?php echo is_null($values['marca']->get('web')) ? 'null' : urlencode($values['marca']->get('web')) ?>",
	"observaciones": "<?php echo is_null($values['marca']->get('observaciones')) ? 'null' : urlencode($values['marca']->get('observaciones')) ?>"
}
<?php endif ?>
