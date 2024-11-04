<?php if (is_null($lineareserva)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $lineareserva->id ?>,
	"idArticulo": <?php echo is_null($lineareserva->id_articulo) ? 'null' : $lineareserva->id_articulo ?>,
	"nombreArticulo": <?php echo is_null($lineareserva->nombre_articulo) ? 'null' : '"' . urlencode($lineareserva->nombre_articulo) . '"' ?>,
	"localizador": <?php echo is_null($lineareserva->id_articulo) ? 'null' : $lineareserva->getArticulo()->localizador ?>,
	"marca": <?php echo is_null($lineareserva->id_articulo) ? 'null' : '"' . urlencode($lineareserva->getArticulo()->getMarca()->nombre) . '"' ?>,
	"stock": <?php echo is_null($lineareserva->id_articulo) ? 'null' : urlencode($lineareserva->getArticulo()->stock) ?>,
	"puc": <?php echo $lineareserva->puc ?>,
	"pvp": <?php echo $lineareserva->pvp ?>,
	"iva": <?php echo $lineareserva->iva ?>,
	"importe": <?php echo $lineareserva->importe ?>,
	"descuento": <?php echo is_null($lineareserva->descuento) ? 'null' : $lineareserva->descuento ?>,
	"importeDescuento": <?php echo is_null($lineareserva->importe_descuento) ? 'null' : $lineareserva->importe_descuento ?>,
	"unidades": <?php echo $lineareserva->unidades ?>
	}
<?php endif ?>
