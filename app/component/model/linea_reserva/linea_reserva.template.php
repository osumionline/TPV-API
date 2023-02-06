<?php if (is_null($values['lineareserva'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['lineareserva']->get('id') ?>,
	"idArticulo": <?php echo is_null($values['lineareserva']->get('id_articulo')) ? 'null' : $values['lineareserva']->get('id_articulo') ?>,
	"nombreArticulo": "<?php echo is_null($values['lineareserva']->get('nombre_articulo')) ? 'null' : urlencode($values['lineareserva']->get('nombre_articulo')) ?>",
	"localizador": <?php echo is_null($values['lineareserva']->get('id_articulo')) ? 'null' : $values['lineareserva']->getArticulo()->get('localizador') ?>,
	"marca": <?php echo is_null($values['lineareserva']->get('id_articulo')) ? 'null' : '"'.urlencode($values['lineareserva']->getArticulo()->getMarca()->get('nombre')).'"' ?>,
	"stock": <?php echo is_null($values['lineareserva']->get('id_articulo')) ? 'null' : urlencode($values['lineareserva']->getArticulo()->get('stock')) ?>,
	"puc": <?php echo $values['lineareserva']->get('puc') ?>,
	"pvp": <?php echo $values['lineareserva']->get('pvp') ?>,
	"iva": <?php echo $values['lineareserva']->get('iva') ?>,
	"importe": <?php echo $values['lineareserva']->get('importe') ?>,
	"descuento": <?php echo is_null($values['lineareserva']->get('descuento')) ? 'null' : $values['lineareserva']->get('descuento') ?>,
	"importeDescuento": <?php echo is_null($values['lineareserva']->get('importe_descuento')) ? 'null' : $values['lineareserva']->get('importe_descuento') ?>,
	"unidades": <?php echo $values['lineareserva']->get('unidades') ?>
}
<?php endif ?>
