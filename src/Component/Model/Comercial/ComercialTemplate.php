<?php if (is_null($comercial)): ?>
	null
<?php else: ?>
	{
	"id": {{ comercial.id }},
	"idProveedor": {{ comercial.id_proveedor | number }},
	"nombre": {{ comercial.nombre | string }},
	"telefono": {{ comercial.telefono | string }},
	"email": {{ comercial.email | string }},
	"observaciones": {{ comercial.observaciones | string }}
	}
<?php endif ?>
