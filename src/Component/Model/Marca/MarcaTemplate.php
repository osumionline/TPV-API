<?php if (is_null($marca)): ?>
	null
<?php else: ?>
	{
	"id": {{ marca.id }},
	"nombre": {{ marca.nombre | string }},
	"foto": <?php echo is_null($marca->getFoto()) ? 'null' : '"' . $marca->getFoto() . '"' ?>,
	"direccion": {{ marca.direccion | string }},
	"telefono": {{ marca.telefono | string }},
	"email": {{ marca.email | string }},
	"web": {{ marca.web | string }},
	"observaciones": {{ marca.observaciones | string }},
	"proveedor": <?php echo is_null($marca->getProveedor()) ? 'null' : '"' . urlencode($marca->getProveedor()->nombre) . '"' ?>
	}
<?php endif ?>
