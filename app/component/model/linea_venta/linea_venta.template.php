<?php if (is_null($values['linea_venta'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['linea_venta']->get('id') ?>,
	"idArticulo": <?php echo is_null($values['linea_venta']->get('id_articulo')) ? 'null' : $values['linea_venta']->get('id_articulo') ?>,
	"articulo": "<?php echo urlencode($values['linea_venta']->get('nombre_articulo')) ?>",
	"localizador": <?php echo is_null($values['linea_venta']->getArticulo()) ? 'null' : $values['linea_venta']->getArticulo()->get('localizador') ?>,
	"marca": <?php echo is_null($values['linea_venta']->getArticulo()) ? 'null' : '"'.urlencode($values['linea_venta']->getArticulo()->getMarca()->get('nombre')).'"' ?>,
	"puc": <?php echo $values['linea_venta']->get('puc') ?>,
	"pvp": <?php echo $values['linea_venta']->get('pvp') ?>,
	"iva": <?php echo $values['linea_venta']->get('iva') ?>,
	"importe": <?php echo $values['linea_venta']->get('importe') ?>,
	"descuento": <?php echo is_null($values['linea_venta']->get('descuento')) ? 'null' : $values['linea_venta']->get('descuento') ?>,
	"importeDescuento": <?php echo is_null($values['linea_venta']->get('importe_descuento')) ? 'null' : $values['linea_venta']->get('importe_descuento') ?>,
	"devuelto": <?php echo $values['linea_venta']->get('devuelto') ?>,
	"unidades": <?php echo $values['linea_venta']->get('unidades') ?>
}
<?php endif ?>
