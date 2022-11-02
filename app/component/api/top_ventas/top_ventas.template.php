<?php foreach ($values['list'] as $i => $item): ?>
  {
    "localizador": <?php echo $item['localizador'] ?>,
    "nombre": "<?php echo urlencode($item['nombre']) ?>",
    "importe": <?php echo $item['importe'] ?>
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
