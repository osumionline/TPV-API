<?php if (is_null($historicoarticulo)): ?>
	null
<?php else: ?>
	{
	"id": {{ historicoarticulo.id }},
	"tipo": {{ historicoarticulo.tipo }},
	"stockPrevio": {{ historicoarticulo.stock_previo }},
	"diferencia": {{ historicoarticulo.diferencia }},
	"stockFinal": {{ historicoarticulo.stock_final }},
	"idVenta": {{ historicoarticulo.id_venta | number }},
	"idPedido": {{ historicoarticulo.id_pedido | number }},
	"puc": {{ historicoarticulo.puc }},
	"pvp": {{ historicoarticulo.pvp }},
	"createdAt": {{ historicoarticulo.created_at | date }}
	}
<?php endif ?>
