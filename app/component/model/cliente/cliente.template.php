<?php if (is_null($values['cliente'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['cliente']->get('id') ?>,
	"nombreApellidos": "<?php echo urlencode($values['cliente']->get('nombre_apellidos')) ?>",
	"dniCif": <?php echo is_null($values['cliente']->get('dni_cif')) ? 'null' : '"'.urlencode($values['cliente']->get('dni_cif')).'"' ?>,
	"telefono": <?php echo is_null($values['cliente']->get('telefono')) ? 'null' : '"'.urlencode($values['cliente']->get('telefono')).'"' ?>,
	"email": <?php echo is_null($values['cliente']->get('email')) ? 'null' : '"'.urlencode($values['cliente']->get('email')).'"' ?>,
	"direccion": <?php echo is_null($values['cliente']->get('direccion')) ? 'null' : '"'.urlencode($values['cliente']->get('direccion')).'"' ?>,
	"codigoPostal": <?php echo is_null($values['cliente']->get('codigo_postal')) ? 'null' : '"'.urlencode($values['cliente']->get('codigo_postal')).'"' ?>,
	"poblacion": <?php echo is_null($values['cliente']->get('poblacion')) ? 'null' : '"'.urlencode($values['cliente']->get('poblacion')).'"' ?>,
	"provincia": <?php echo is_null($values['cliente']->get('provincia')) ? 'null' : $values['cliente']->get('provincia') ?>,
	"factIgual": <?php echo $values['cliente']->get('fact_igual') ? 'true' : 'false' ?>,
	"factNombreApellidos": <?php echo is_null($values['cliente']->get('fact_nombre_apellidos')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_nombre_apellidos')).'"' ?>,
	"factDniCif": <?php echo is_null($values['cliente']->get('fact_dni_cif')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_dni_cif')).'"' ?>,
	"factTelefono": <?php echo is_null($values['cliente']->get('fact_telefono')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_telefono')).'"' ?>,
	"factEmail": <?php echo is_null($values['cliente']->get('fact_email')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_email')).'"' ?>,
	"factDireccion": <?php echo is_null($values['cliente']->get('fact_direccion')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_direccion')).'"' ?>,
	"factCodigoPostal": <?php echo is_null($values['cliente']->get('fact_codigo_postal')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_codigo_postal')).'"' ?>,
	"factPoblacion": <?php echo is_null($values['cliente']->get('fact_poblacion')) ? 'null' : '"'.urlencode($values['cliente']->get('fact_poblacion')).'"' ?>,
	"factProvincia": <?php echo is_null($values['cliente']->get('fact_provincia')) ? 'null' : $values['cliente']->get('fact_provincia') ?>,
	"observaciones": <?php echo is_null($values['cliente']->get('observaciones')) ? 'null' : '"'.urlencode($values['cliente']->get('observaciones')).'"' ?>,
	"descuento": <?php echo $values['cliente']->get('descuento') ?>,
	"ultimaVenta": <?php echo is_null($values['cliente']->getUltimaVenta()) ? 'null' : '"'.$values['cliente']->getUltimaVenta()->get('created_at', 'd/m/Y H:i').'"' ?>
}
<?php endif ?>
