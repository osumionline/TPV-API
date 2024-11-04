<?php if (is_null($comercial)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $comercial->id ?>,
	"idProveedor": <?php echo is_null($comercial->id_proveedor) ? 'null' : $comercial->id_proveedor ?>,
	"nombre": "<?php echo urlencode($comercial->nombre) ?>",
	"telefono": <?php echo is_null($comercial->telefono) ? 'null' : '"' . urlencode($comercial->telefono) . '"' ?>,
	"email": <?php echo is_null($comercial->email) ? 'null' : '"' . urlencode($comercial->email) . '"' ?>,
	"observaciones": <?php echo is_null($comercial->observaciones) ? 'null' : '"' . urlencode($comercial->observaciones) . '"' ?>
	}
<?php endif ?>
