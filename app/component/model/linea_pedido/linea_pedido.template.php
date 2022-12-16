<?php if (is_null($values['linea_pedido'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['linea_pedido']->get('id') ?>,
	"idArticulo": <?php echo $values['linea_pedido']->get('id_articulo') ?>,
	"localizador": <?php echo $values['linea_pedido']->getArticulo()->get('localizador') ?>,
	"descripcion": "<?php echo urlencode($values['linea_pedido']->getArticulo()->get('nombre')) ?>",
	"idMarca": <?php echo $values['linea_pedido']->getArticulo()->get('id_marca') ?>,
	"marca": "<?php echo urlencode($values['linea_pedido']->getArticulo()->getMarca()->get('nombre')) ?>",
	"unidades": <?php echo $values['linea_pedido']->get('unidades') ?>,
	"palb": <?php echo $values['linea_pedido']->get('palb') ?>,
	"pvp": <?php echo $values['linea_pedido']->get('pvp') ?>,
	"stock": <?php echo $values['linea_pedido']->getArticulo()->get('stock') ?>,
	"iva": <?php echo $values['linea_pedido']->get('iva') ?>,
	"re": <?php echo $values['linea_pedido']->get('re') ?>,
	"descuento": <?php echo $values['linea_pedido']->get('descuento') ?>,
	"idCategoria": <?php echo $values['linea_pedido']->getArticulo()->get('id_categoria') ?>,
	"codBarras": null,
	"referencia": "<?php echo urlencode($values['linea_pedido']->getArticulo()->get('referencia')) ?>"
}
<?php endif ?>
