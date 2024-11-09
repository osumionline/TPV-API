<?php if (is_null($cliente)): ?>
	null
<?php else: ?>
	{
	"id": {{ cliente.id }},
	"nombreApellidos": {{ cliente.nombre_apellidos | string }},
	"dniCif": {{ cliente.dni_cif | string }},
	"telefono": {{ cliente.telefono | string }},
	"email": {{ cliente.email | string }},
	"direccion": {{ cliente.direccion | string }},
	"codigoPostal": {{ cliente.codigo_postal | string }},
	"poblacion": {{ cliente.poblacion | string }},
	"provincia": {{ cliente.provincia | number }},
	"factIgual": {{ cliente.fact_igual | bool }},
	"factNombreApellidos": {{ cliente.fact_nombre_apellidos | string }},
	"factDniCif": {{ cliente.fact_dni_cif | string }},
	"factTelefono": {{ cliente.fact_telefono | string }},
	"factEmail": {{ cliente.fact_email | string }},
	"factDireccion": {{ cliente.fact_direccion | string }},
	"factCodigoPostal": {{ cliente.fact_codigo_postal | string }},
	"factPoblacion": {{ cliente.fact_poblacion | string }},
	"factProvincia": {{ cliente.fact_provincia | number }},
	"observaciones": {{ cliente.observaciones | string }},
	"descuento": {{ cliente.descuento }},
	"ultimaVenta": <?php echo is_null($cliente->getUltimaVenta()) ? 'null' : '"' . $cliente->getUltimaVenta()->get('created_at', 'd/m/Y H:i') . '"' ?>
	}
<?php endif ?>
