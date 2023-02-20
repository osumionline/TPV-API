<?php if (is_null($values['linea_reserva'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['linea_reserva']->get('id') ?>,
	"idArticulo": <?php echo is_null($values['linea_reserva']->get('id_articulo')) ? 'null' : $values['linea_reserva']->get('id_articulo') ?>,
	"nombreArticulo": "<?php echo is_null($values['linea_reserva']->get('nombre_articulo')) ? 'null' : urlencode($values['linea_reserva']->get('nombre_articulo')) ?>",
	"localizador": <?php echo is_null($values['linea_reserva']->get('id_articulo')) ? 'null' : $values['linea_reserva']->getArticulo()->get('localizador') ?>,
	"marca": <?php echo is_null($values['linea_reserva']->get('id_articulo')) ? 'null' : '"'.urlencode($values['linea_reserva']->getArticulo()->getMarca()->get('nombre')).'"' ?>,
	"stock": <?php echo is_null($values['linea_reserva']->get('id_articulo')) ? 'null' : urlencode($values['linea_reserva']->getArticulo()->get('stock')) ?>,
	"puc": <?php echo $values['linea_reserva']->get('puc') ?>,
	"pvp": <?php echo $values['linea_reserva']->get('pvp') ?>,
	"iva": <?php echo $values['linea_reserva']->get('iva') ?>,
	"importe": <?php echo $values['linea_reserva']->get('importe') ?>,
	"descuento": <?php echo is_null($values['linea_reserva']->get('descuento')) ? 'null' : $values['linea_reserva']->get('descuento') ?>,
	"importeDescuento": <?php echo is_null($values['linea_reserva']->get('importe_descuento')) ? 'null' : $values['linea_reserva']->get('importe_descuento') ?>,
	"unidades": <?php echo $values['linea_reserva']->get('unidades') ?>
}
<?php endif ?>
