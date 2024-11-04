<?php if (is_null($cliente)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $cliente->id ?>,
	"nombreApellidos": "<?php echo urlencode($cliente->nombre_apellidos) ?>",
	"dniCif": <?php echo is_null($cliente->dni_cif) ? 'null' : '"' . urlencode($cliente->dni_cif) . '"' ?>,
	"telefono": <?php echo is_null($cliente->telefono) ? 'null' : '"' . urlencode($cliente->telefono) . '"' ?>,
	"email": <?php echo is_null($cliente->email) ? 'null' : '"' . urlencode($cliente->email) . '"' ?>,
	"direccion": <?php echo is_null($cliente->direccion) ? 'null' : '"' . urlencode($cliente->direccion) . '"' ?>,
	"codigoPostal": <?php echo is_null($cliente->codigo_postal) ? 'null' : '"' . urlencode($cliente->codigo_postal) . '"' ?>,
	"poblacion": <?php echo is_null($cliente->poblacion) ? 'null' : '"' . urlencode($cliente->poblacion) . '"' ?>,
	"provincia": <?php echo is_null($cliente->provincia) ? 'null' : $cliente->provincia ?>,
	"factIgual": <?php echo $cliente->fact_igual ? 'true' : 'false' ?>,
	"factNombreApellidos": "<?php echo urlencode($cliente->fact_nombre_apellidos) ?>",
	"factDniCif": <?php echo is_null($cliente->fact_dni_cif) ? 'null' : '"' . urlencode($cliente->fact_dni_cif) . '"' ?>,
	"factTelefono": <?php echo is_null($cliente->fact_telefono) ? 'null' : '"' . urlencode($cliente->fact_telefono) . '"' ?>,
	"factEmail": <?php echo is_null($cliente->fact_email) ? 'null' : '"' . urlencode($cliente->fact_email) . '"' ?>,
	"factDireccion": <?php echo is_null($cliente->fact_direccion) ? 'null' : '"' . urlencode($cliente->fact_direccion) . '"' ?>,
	"factCodigoPostal": <?php echo is_null($cliente->fact_codigo_postal) ? 'null' : '"' . urlencode($cliente->fact_codigo_postal) . '"' ?>,
	"factPoblacion": <?php echo is_null($cliente->fact_poblacion) ? 'null' : '"' . urlencode($cliente->fact_poblacion) . '"' ?>,
	"factProvincia": <?php echo is_null($cliente->fact_provincia) ? 'null' : $cliente->fact_provincia ?>,
	"observaciones": <?php echo is_null($cliente->observaciones) ? 'null' : '"' . urlencode($cliente->observaciones) . '"' ?>,
	"descuento": <?php echo $cliente->descuento ?>,
	"ultimaVenta": <?php echo is_null($cliente->getUltimaVenta()) ? 'null' : '"' . $cliente->getUltimaVenta()->get('created_at', 'd/m/Y H:i') . '"' ?>
	}
<?php endif ?>
