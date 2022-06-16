<?php if (count($values['datos'])>0): ?>
	{
		"efectivo": <?php echo $values['datos']['efectivo'] ?>,
		"cambio": <?php echo $values['datos']['cambio'] ?>,
		"tarjeta": <?php echo is_null($values['datos']['tarjeta']) ? 'null' : $values['datos']['tarjeta'] ?>,
		"idEmpleado": <?php echo $values['datos']['idEmpleado'] ?>,
		"idTipoPago": <?php echo $values['datos']['idTipoPago'] ?>,
		"idCliente": <?php echo $values['datos']['idCliente'] ?>,
		"total": <?php echo $values['datos']['total'] ?>,
		"lineas": [
<?php foreach ($values['datos']['lineas'] as $i => $linea): ?>
			{
				"idArticulo": <?php echo $linea->get('id_articulo') ?>,
				"localizador": <?php echo $linea->getArticulo()->get('localizador') ?>,
				"descripcion": "<?php echo urlencode($linea->getArticulo()->get('nombre')) ?>",
				"marca": "<?php echo urlencode($linea->getArticulo()->getMarca()->get('nombre')) ?>",
				"stock": <?php echo $linea->getArticulo()->get('stock') ?>,
				"cantidad": <?php echo $linea->get('unidades') ?>,
				"pvp": <?php echo $linea->get('pvp') ?>,
				"importe": <?php echo $linea->get('importe') ?>,
				"descuento": <?php echo is_null($linea->get('importe_descuento')) ? $linea->get('descuento') : $linea->get('importe_descuento') ?>,
				"descuentoManual": <?php echo is_null($linea->get('importe_descuento')) ? 'false' : 'true' ?>,
				"observaciones": "<?php echo urlencode($linea->getArticulo()->get('observaciones')) ?>"
			}<?php if ($i<count($values['datos']['lineas'])-1): ?>,<?php endif ?>
<?php endforeach ?>
		],
		"pagoMixto": <?php echo $values['datos']['pagoMixto'] ? 'true' : 'false' ?>,
		"factura": <?php echo $values['datos']['factura'] ? 'true' : 'false' ?>
	}
<?php else: ?>
	null
<?php endif ?>