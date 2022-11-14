<?php if (is_null($values['comercial'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['comercial']->get('id') ?>,
	"idProveedor": <?php echo $values['comercial']->get('id_proveedor') ?>,
	"nombre": "<?php echo urlencode($values['comercial']->get('nombre')) ?>",
	"telefono": "<?php echo is_null($values['comercial']->get('telefono')) ? 'null' : urlencode($values['comercial']->get('telefono')) ?>",
	"email": "<?php echo is_null($values['comercial']->get('email')) ? 'null' : urlencode($values['comercial']->get('email')) ?>",
	"observaciones": "<?php echo is_null($values['comercial']->get('observaciones')) ? 'null' : urlencode($values['comercial']->get('observaciones')) ?>"
}
<?php endif ?>
