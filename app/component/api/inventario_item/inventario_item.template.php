<?php if (is_null($values['item'])): ?>
null
<?php else: ?>
  {
    "id": <?php echo $values['item']['id'] ?>,
    "localizador": <?php echo $values['item']['localizador'] ?>,
    "marca": "<?php echo urlencode($values['item']['marca']) ?>",
    "proveedor": <?php echo is_null($values['item']['proveedor']) ? 'null' : '"'.urlencode($values['item']['proveedor']).'"' ?>,
    "referencia": "<?php echo urlencode($values['item']['referencia']) ?>",
    "nombre": "<?php echo urlencode($values['item']['nombre']) ?>",
    "stock": <?php echo $values['item']['stock'] ?>,
    "puc": <?php echo $values['item']['puc'] ?>,
    "pvp": <?php echo $values['item']['pvp'] ?>,
    "hasCodigosBarras": <?php echo $values['item']['has_codigos_barras'] ? 'true' : 'false' ?>,
    "observaciones": "<?php echo urlencode($values['item']['observaciones']) ?>"
  }
<?php endif ?>
