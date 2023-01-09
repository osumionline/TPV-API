<?php
use OsumiFramework\App\Component\Model\VentaListComponent;

if (is_null($values['factura'])){
?>
null
<?php
}
else{
?>
{
	"id": <?php echo $values['factura']->get('id') ?>,
	"numFactura": <?php echo $values['factura']->get('num_factura') ?>,
	"nombreApellidos": "<?php echo urlencode($values['factura']->get('nombre_apellidos')) ?>",
	"dniCif": "<?php echo is_null($values['factura']->get('dni_cif')) ? 'null' : urlencode($values['factura']->get('dni_cif')) ?>",
	"telefono": "<?php echo is_null($values['factura']->get('telefono')) ? 'null' : urlencode($values['factura']->get('telefono')) ?>",
	"email": "<?php echo is_null($values['factura']->get('email')) ? 'null' : urlencode($values['factura']->get('email')) ?>",
	"direccion": "<?php echo is_null($values['factura']->get('direccion')) ? 'null' : urlencode($values['factura']->get('direccion')) ?>",
	"codigoPostal": "<?php echo is_null($values['factura']->get('codigo_postal')) ? 'null' : urlencode($values['factura']->get('codigo_postal')) ?>",
	"poblacion": "<?php echo is_null($values['factura']->get('poblacion')) ? 'null' : urlencode($values['factura']->get('poblacion')) ?>",
	"provincia": <?php echo is_null($values['factura']->get('provincia')) ? 'null' : $values['factura']->get('provincia') ?>,
	"importe": <?php echo $values['factura']->get('importe') ?>,
	"impresa": <?php echo $values['factura']->get('impresa') ? 'true' : 'false' ?>,
	"fecha": "<?php echo $values['factura']->get('created_at', 'd/m/Y H:i') ?>",
	"ventas": [<?php echo new VentaListComponent(['list' => $values['factura']->getVentas()]) ?>]
}
<?php
}
?>
