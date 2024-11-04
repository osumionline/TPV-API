<?php if (is_null($lineapedido)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $lineapedido->id ?>,
	"idArticulo": <?php echo $lineapedido->id_articulo ?>,
	"nombreArticulo": <?php echo is_null($lineapedido->nombre_articulo) ? 'null' : '"' . urlencode($lineapedido->nombre_articulo) . '"' ?>,
	"localizador": <?php echo $lineapedido->getArticulo()->localizador ?>,
	"idMarca": <?php echo $lineapedido->getArticulo()->id_marca ?>,
	"marca": "<?php echo urlencode($lineapedido->getArticulo()->getMarca()->nombre) ?>",
	"unidades": <?php echo $lineapedido->unidades ?>,
	"palb": <?php echo $lineapedido->palb ?>,
	"puc": <?php echo $lineapedido->puc ?>,
	"pvp": <?php echo $lineapedido->pvp ?>,
	"margen": <?php echo $lineapedido->margen ?>,
	"stock": <?php echo $lineapedido->getArticulo()->stock ?>,
	"iva": <?php echo $lineapedido->iva ?>,
	"re": <?php echo is_null($lineapedido->re) ? 'null' : $lineapedido->re ?>,
	"descuento": <?php echo $lineapedido->descuento ?>,
	"idCategoria": <?php echo is_null($lineapedido->id_categoria) ? 'null' : $lineapedido->id_categoria ?>,
	"codBarras": <?php echo is_null($lineapedido->codigo_barras) ? 'null' : '"' . urlencode($lineapedido->codigo_barras) . '"' ?>,
	"hasCodBarras": <?php echo $lineapedido->getArticulo()->hasCodigoBarras() ? 'true' : 'false' ?>,
	"referencia": "<?php echo urlencode($lineapedido->getArticulo()->referencia) ?>"
	}
<?php endif ?>
