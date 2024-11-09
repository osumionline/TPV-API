<?php if (is_null($lineapedido)): ?>
	null
<?php else: ?>
	{
	"id": {{ lineapedido.id }},
	"idArticulo": {{ lineapedido.id_articulo }}ç,
	"nombreArticulo": {{ lineapedido.nombre_articulo | string }},
	"localizador": <?php echo $lineapedido->getArticulo()->localizador ?>,
	"idMarca": <?php echo $lineapedido->getArticulo()->id_marca ?>,
	"marca": "<?php echo urlencode($lineapedido->getArticulo()->getMarca()->nombre) ?>",
	"unidades": {{ lineapedido.unidades }},
	"palb": {{ lineapedido.palb }},
	"puc": {{ lineapedido.puc }},
	"pvp": {{ lineapedido.pvp }},
	"margen": {{ lineapedido.margen }},
	"stock": <?php echo $lineapedido->getArticulo()->stock ?>,
	"iva": {{ lineapedido.iva }},
	"re": {{ lineapedido.re | number }},
	"descuento": {{ lineapedido.descuento }},
	"idCategoria": {{ lineapedido.id_categoria | number }},
	"codBarras": {{ lineapedido.codigo_barras | string }},
	"hasCodBarras": <?php echo $lineapedido->getArticulo()->hasCodigoBarras() ? 'true' : 'false' ?>,
	"referencia": "<?php echo urlencode($lineapedido->getArticulo()->referencia) ?>"
	}
<?php endif ?>
