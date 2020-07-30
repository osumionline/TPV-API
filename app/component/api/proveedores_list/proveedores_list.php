<?php foreach ($values['list'] as $i => $proveedor): ?>
	{
		"id": <?php echo $proveedor->get('id') ?>,
		"nombre": "<?php echo urlencode($proveedor->get('nombre')) ?>",
		"direccion": "<?php echo urlencode($proveedor->get('nombre')) ?>",
		"telefono": "<?php echo urlencode($proveedor->get('telefono')) ?>",
		"email": "<?php echo urlencode($proveedor->get('email')) ?>",
		"web": "<?php echo urlencode($proveedor->get('web')) ?>",
		"observaciones": "<?php echo urlencode($proveedor->get('observaciones')) ?>"
	}<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>