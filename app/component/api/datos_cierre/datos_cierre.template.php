<?php if (is_null($values['datos'])): ?>
null
<?php else: ?>
  {
    "saldoInicial": <?php echo $values['datos']['saldo_inicial'] ?>,
    "importeEfectivo": <?php echo $values['datos']['importe_efectivo'] ?>,
    "salidasCaja": <?php echo $values['datos']['salidas_caja'] ?>,
    "saldoFinal": <?php echo $values['datos']['saldo_final'] ?>,
    "tipos": [
<?php foreach ($values['datos']['tipos'] as $i => $tipo): ?>
      {
        "id": <?php echo $tipo['id'] ?>,
        "nombre": "<?php echo urlencode($tipo['nombre']) ?>",
        "ventas": <?php echo $tipo['ventas'] ?>,
        "operaciones": <?php echo $tipo['operaciones'] ?>
      }<?php if ($i<count($values['datos']['tipos'])-1): ?>,<?php endif ?>
<?php endforeach ?>
    ]
  }
<?php endif ?>
