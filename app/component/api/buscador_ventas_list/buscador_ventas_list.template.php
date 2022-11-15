<?php foreach ($values['list'] as $i => $item): ?>
  {
    "localizador": <?php echo $item['localizador'] ?>,
    "nombre": "<?php echo urlencode($item['nombre']) ?>",
    "marca": "<?php echo urlencode($item['marca']) ?>",
    "pvp": <?php echo $item['pvp'] ?>,
    "stock": <?php echo $item['stock'] ?>
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>
