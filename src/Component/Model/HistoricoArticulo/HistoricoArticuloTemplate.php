<?php if (is_null($historicoarticulo)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $historicoarticulo->id ?>,
	"tipo": <?php echo $historicoarticulo->tipo ?>,
	"stockPrevio": <?php echo $historicoarticulo->stock_previo ?>,
	"diferencia": <?php echo $historicoarticulo->diferencia ?>,
	"stockFinal": <?php echo $historicoarticulo->stock_final ?>,
	"idVenta": <?php echo is_null($historicoarticulo->id_venta) ? 'null' : $historicoarticulo->id_venta ?>,
	"idPedido": <?php echo is_null($historicoarticulo->id_pedido) ? 'null' : $historicoarticulo->id_pedido ?>,
	"puc": <?php echo $historicoarticulo->puc ?>,
	"pvp": <?php echo $historicoarticulo->pvp ?>,
	"createdAt": "<?php echo $historicoarticulo->get('created_at', 'd/m/Y H:i:s') ?>"
	}
<?php endif ?>
