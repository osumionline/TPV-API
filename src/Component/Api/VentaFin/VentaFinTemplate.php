<?php if (count($datos) > 0): ?>
	{
		"efectivo": <?php echo $datos['efectivo'] ?>,
		"cambio": <?php echo $datos['cambio'] ?>,
		"tarjeta": <?php echo is_null($datos['tarjeta']) ? 'null' : $datos['tarjeta'] ?>,
		"idEmpleado": <?php echo $datos['idEmpleado'] ?>,
		"idTipoPago": <?php echo $datos['idTipoPago'] ?>,
		"idCliente": <?php echo $datos['idCliente'] ?>,
		"total": <?php echo $datos['total'] ?>,
		"lineas": [
<?php foreach ($datos['lineas'] as $i => $linea): ?>
			{
				"idArticulo": <?php echo $linea->id_articulo ?>,
				"localizador": <?php echo $linea->getArticulo()->localizador ?>,
				"descripcion": "<?php echo urlencode($linea->getArticulo()->nombre) ?>",
				"marca": "<?php echo urlencode($linea->getArticulo()->getMarca()->nombre) ?>",
				"stock": <?php echo $linea->getArticulo()->stock ?>,
				"cantidad": <?php echo $linea->unidades ?>,
				"pvp": <?php echo $linea->pvp ?>,
				"importe": <?php echo $linea->importe ?>,
				"descuento": <?php echo is_null($linea->importe_descuento) ? $linea->descuento : $linea->importe_descuento ?>,
				"descuentoManual": <?php echo is_null($linea->importe_descuento) ? 'false' : 'true' ?>,
				"observaciones": "<?php echo urlencode($linea->getArticulo()->observaciones) ?>"
			}<?php if ($i < count($datos['lineas']) - 1): ?>,<?php endif ?>
<?php endforeach ?>
		],
		"pagoMixto": <?php echo $datos['pagoMixto'] ? 'true' : 'false' ?>,
		"factura": <?php echo $datos['factura'] ? 'true' : 'false' ?>
	}
<?php else: ?>
	null
<?php endif ?>
