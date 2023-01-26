<?php foreach ($values['list'] as $i => $item): ?>
  {
    "localizador": <?php echo !is_null($item['localizador']) ? $item['localizador'] : 'null' ?>,
    "nombre": "<?php echo urlencode($item['nombre']) ?>",
    "importe": <?php echo $item['importe'] ?>
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
