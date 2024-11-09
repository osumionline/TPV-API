<?php if (is_null($lineareserva)): ?>
	null
<?php else: ?>
	{
	"id": {{ lineareserva.id }},
	"idArticulo": {{ lineareserva.id_articulo | number }},
	"nombreArticulo": {{ lineareserva.nombre_articulo | string }},
	"localizador": <?php echo is_null($lineareserva->id_articulo) ? 'null' : $lineareserva->getArticulo()->localizador ?>,
	"marca": <?php echo is_null($lineareserva->id_articulo) ? 'null' : '"' . urlencode($lineareserva->getArticulo()->getMarca()->nombre) . '"' ?>,
	"stock": <?php echo is_null($lineareserva->id_articulo) ? 'null' : urlencode($lineareserva->getArticulo()->stock) ?>,
	"puc": {{ lineareserva.puc }},
	"pvp": {{ lineareserva.pvp }},
	"iva": {{ lineareserva.iva }},
	"importe": {{ lineareserva.importe }},
	"descuento": {{ lineareserva.descuento | number }},
	"importeDescuento": {{ lineareserva.importe_descuento | number }},
	"unidades": {{lineareserva.unidades }}
	}
<?php endif ?>
