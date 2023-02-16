<?php if (is_null($values['linea_pedido'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['linea_pedido']->get('id') ?>,
	"idArticulo": <?php echo $values['linea_pedido']->get('id_articulo') ?>,
	"nombreArticulo": "<?php echo urlencode($values['recepcionado'] ? $values['linea_pedido']->get('nombre_articulo') : $values['linea_pedido']->getArticulo()->get('nombre')) ?>",
	"localizador": <?php echo $values['linea_pedido']->getArticulo()->get('localizador') ?>,
	"idMarca": <?php echo $values['linea_pedido']->getArticulo()->get('id_marca') ?>,
	"marca": "<?php echo urlencode($values['linea_pedido']->getArticulo()->getMarca()->get('nombre')) ?>",
	"unidades": <?php echo $values['linea_pedido']->get('unidades') ?>,
	"palb": <?php echo $values['linea_pedido']->get('palb') ?>,
	"puc": <?php echo $values['linea_pedido']->get('puc') ?>,
	"pvp": <?php echo $values['linea_pedido']->get('pvp') ?>,
	"margen": <?php echo $values['linea_pedido']->get('margen') ?>,
	"stock": <?php echo $values['linea_pedido']->getArticulo()->get('stock') ?>,
	"iva": <?php echo $values['linea_pedido']->get('iva') ?>,
	"re": <?php echo is_null($values['linea_pedido']->get('re')) ? 'null' : $values['linea_pedido']->get('re') ?>,
	"descuento": <?php echo $values['linea_pedido']->get('descuento') ?>,
	"idCategoria": <?php echo is_null($values['linea_pedido']->get('id_categoria')) ? 'null' : $values['linea_pedido']->get('id_categoria') ?>,
	"codBarras": <?php echo is_null($values['linea_pedido']->get('codigo_barras')) ? 'null' : $values['linea_pedido']->get('codigo_barras') ?>,
	"hasCodBarras": <?php echo $values['linea_pedido']->getArticulo()->hasCodigoBarras() ? 'true' : 'false' ?>,
	"referencia": "<?php echo urlencode($values['linea_pedido']->getArticulo()->get('referencia')) ?>"
}
<?php endif ?>
