<?php if (is_null($values['datos'])): ?>
null
<?php else: ?>
  {
    "date": "<?php echo $values['datos']['date'] ?>",
    "saldoInicial": <?php echo $values['datos']['saldo_inicial'] ?>,
    "importeEfectivo": <?php echo $values['datos']['importe_efectivo'] ?>,
    "salidasCaja": <?php echo $values['datos']['salidas_caja'] ?>,
    "saldoFinal": <?php echo $values['datos']['saldo_final'] ?>,
    "real": <?php echo $values['datos']['real'] ?>,
    "importe1c": <?php echo $values['datos']['importe1c'] ?>,
    "importe2c": <?php echo $values['datos']['importe2c'] ?>,
    "importe5c": <?php echo $values['datos']['importe5c'] ?>,
    "importe10c": <?php echo $values['datos']['importe10c'] ?>,
    "importe20c": <?php echo $values['datos']['importe20c'] ?>,
    "importe50c": <?php echo $values['datos']['importe50c'] ?>,
    "importe1": <?php echo $values['datos']['importe1'] ?>,
    "importe2": <?php echo $values['datos']['importe2'] ?>,
    "importe5": <?php echo $values['datos']['importe5'] ?>,
    "importe10": <?php echo $values['datos']['importe10'] ?>,
    "importe20": <?php echo $values['datos']['importe20'] ?>,
    "importe50": <?php echo $values['datos']['importe50'] ?>,
    "importe100": <?php echo $values['datos']['importe100'] ?>,
    "importe200": <?php echo $values['datos']['importe200'] ?>,
    "importe500": <?php echo $values['datos']['importe500'] ?>,
    "retirado": <?php echo $values['datos']['retirado'] ?>,
    "entrada": <?php echo $values['datos']['entrada'] ?>,
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
