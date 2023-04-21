<?php if (is_null($values['historico_articulo'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['historico_articulo']->get('id') ?>,
	"tipo": <?php echo $values['historico_articulo']->get('tipo') ?>,
	"stockPrevio": <?php echo $values['historico_articulo']->get('stock_previo') ?>,
	"diferencia": <?php echo $values['historico_articulo']->get('diferencia') ?>,
	"stockFinal": <?php echo $values['historico_articulo']->get('stock_final') ?>,
	"idVenta": <?php echo is_null($values['historico_articulo']->get('id_venta')) ? 'null' : $values['historico_articulo']->get('id_venta') ?>,
	"idPedido": <?php echo is_null($values['historico_articulo']->get('id_pedido')) ? 'null' : $values['historico_articulo']->get('id_pedido') ?>,
	"puc": <?php echo $values['historico_articulo']->get('puc') ?>,
	"pvp": <?php echo $values['historico_articulo']->get('pvp') ?>,
	"createdAt": "<?php echo $values['historico_articulo']->get('created_at', 'd/m/Y H:i') ?>"
}
<?php endif ?>
