<?php if (is_null($item)): ?>
null
<?php else: ?>
  {
    "id": <?php echo $item['id'] ?>,
    "localizador": <?php echo $item['localizador'] ?>,
    "marca": "<?php echo urlencode($item['marca']) ?>",
    "proveedor": <?php echo is_null($item['proveedor']) ? 'null' : '"'.urlencode($item['proveedor']).'"' ?>,
    "referencia": "<?php echo urlencode($item['referencia']) ?>",
    "nombre": "<?php echo urlencode($item['nombre']) ?>",
    "stock": <?php echo $item['stock'] ?>,
    "puc": <?php echo $item['puc'] ?>,
    "pvp": <?php echo $item['pvp'] ?>,
    "hasCodigosBarras": <?php echo $item['has_codigos_barras'] ? 'true' : 'false' ?>,
    "observaciones": "<?php echo urlencode($item['observaciones']) ?>"
  }
<?php endif ?>
