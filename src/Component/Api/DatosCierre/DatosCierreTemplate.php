<?php if (is_null($datos)): ?>
null
<?php else: ?>
  {
    "date": "<?php echo $datos['date'] ?>",
    "saldoInicial": <?php echo $datos['saldo_inicial'] ?>,
    "importeEfectivo": <?php echo $datos['importe_efectivo'] ?>,
    "salidasCaja": <?php echo $datos['salidas_caja'] ?>,
    "saldoFinal": <?php echo $datos['saldo_final'] ?>,
    "real": <?php echo $datos['real'] ?>,
    "importe1c": <?php echo $datos['importe1c'] ?>,
    "importe2c": <?php echo $datos['importe2c'] ?>,
    "importe5c": <?php echo $datos['importe5c'] ?>,
    "importe10c": <?php echo $datos['importe10c'] ?>,
    "importe20c": <?php echo $datos['importe20c'] ?>,
    "importe50c": <?php echo $datos['importe50c'] ?>,
    "importe1": <?php echo $datos['importe1'] ?>,
    "importe2": <?php echo $datos['importe2'] ?>,
    "importe5": <?php echo $datos['importe5'] ?>,
    "importe10": <?php echo $datos['importe10'] ?>,
    "importe20": <?php echo $datos['importe20'] ?>,
    "importe50": <?php echo $datos['importe50'] ?>,
    "importe100": <?php echo $datos['importe100'] ?>,
    "importe200": <?php echo $datos['importe200'] ?>,
    "importe500": <?php echo $datos['importe500'] ?>,
    "retirado": <?php echo $datos['retirado'] ?>,
    "entrada": <?php echo $datos['entrada'] ?>,
    "tipos": [
<?php foreach ($datos['tipos'] as $i => $tipo): ?>
      {
        "id": <?php echo $tipo['id'] ?>,
        "nombre": "<?php echo urlencode($tipo['nombre']) ?>",
        "ventas": <?php echo $tipo['ventas'] ?>,
        "operaciones": <?php echo $tipo['operaciones'] ?>
      }<?php if ($i < count($datos['tipos']) - 1): ?>,<?php endif ?>
<?php endforeach ?>
    ]
  }
<?php endif ?>
