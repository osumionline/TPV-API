<?php if (is_null($lineaventa)): ?>
	null
<?php else: ?>
	{
	"id": {{ lineaventa.id }},
	"idArticulo": {{ lineaventa.id_articulo | number }},
	"articulo": {{ lineaventa.nombre_articulo | string }},
	"localizador": <?php echo is_null($lineaventa->getArticulo()) ? 'null' : $lineaventa->getArticulo()->localizador ?>,
	"marca": <?php echo is_null($lineaventa->getArticulo()) ? 'null' : '"' . urlencode($lineaventa->getArticulo()->getMarca()->nombre) . '"' ?>,
	"puc": {{ lineaventa.puc }},
	"pvp": {{ lineaventa.pvp }},
	"iva": {{ lineaventa.iva }},
	"importe": {{ lineaventa.importe }},
	"descuento": {{ lineaventa.descuento | number }},
	"importeDescuento": {{ lineaventa.importe_descuento | number }},
	"devuelto": {{ lineaventa.devuelto }},
	"unidades": {{ lineaventa.unidades }},
	"regalo": {{ lineaventa.regalo | bool }}
	}
<?php endif ?>
