<?php if (is_null($marca)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $marca->id ?>,
	"nombre": "<?php echo urlencode($marca->nombre) ?>",
	"foto": <?php echo is_null($marca->getFoto()) ? 'null' : '"' . $marca->getFoto() . '"' ?>,
	"direccion": <?php echo is_null($marca->direccion) ? 'null' : '"' . urlencode($marca->direccion) . '"' ?>,
	"telefono": <?php echo is_null($marca->telefono) ? 'null' : '"' . urlencode($marca->telefono) . '"' ?>,
	"email": <?php echo is_null($marca->email) ? 'null' : '"' . urlencode($marca->email) . '"' ?>,
	"web": <?php echo is_null($marca->web) ? 'null' : '"' . urlencode($marca->web) . '"' ?>,
	"observaciones": <?php echo is_null($marca->observaciones) ? 'null' : '"' . urlencode($marca->observaciones) . '"' ?>,
	"proveedor": <?php echo is_null($marca->getProveedor()) ? 'null' : '"' . urlencode($marca->getProveedor()->nombre) . '"' ?>
	}
<?php endif ?>
