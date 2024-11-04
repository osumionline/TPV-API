<?php if (is_null($lineaventa)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $lineaventa->id ?>,
	"idArticulo": <?php echo is_null($lineaventa->id_articulo) ? 'null' : $lineaventa->id_articulo ?>,
	"articulo": <?php echo is_null($lineaventa->nombre_articulo) ? 'null' : '"' . urlencode($lineaventa->nombre_articulo) . '"' ?>,
	"localizador": <?php echo is_null($lineaventa->getArticulo()) ? 'null' : $lineaventa->getArticulo()->localizador ?>,
	"marca": <?php echo is_null($lineaventa->getArticulo()) ? 'null' : '"' . urlencode($lineaventa->getArticulo()->getMarca()->nombre) . '"' ?>,
	"puc": <?php echo $lineaventa->puc ?>,
	"pvp": <?php echo $lineaventa->pvp ?>,
	"iva": <?php echo $lineaventa->iva ?>,
	"importe": <?php echo $lineaventa->importe ?>,
	"descuento": <?php echo is_null($lineaventa->descuento) ? 'null' : $lineaventa->descuento ?>,
	"importeDescuento": <?php echo is_null($lineaventa->importe_descuento) ? 'null' : $lineaventa->importe_descuento ?>,
	"devuelto": <?php echo $lineaventa->devuelto ?>,
	"unidades": <?php echo $lineaventa->unidades ?>,
	"regalo": <?php echo $lineaventa->regalo ? 'true' : 'false' ?>
	}
<?php endif ?>
