<?php foreach ($values['list'] as $i => $item): ?>
  {
    "fecha": "<?php echo $item['fecha'] ?>",
    "localizador": <?php echo !is_null($item['localizador']) ? $item['localizador'] : 'null' ?>,
    "nombre": "<?php echo urlencode($item['nombre']) ?>",
    "unidades": <?php echo $item['unidades'] ?>,
    "pvp": <?php echo $item['pvp'] ?>,
    "importe": <?php echo $item['importe'] ?>
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
