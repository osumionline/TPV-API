<?php foreach ($values['list'] as $i => $marca): ?>
  {
    "id": <?php echo $marca->get('id') ?>,
    "nombre": "<?php echo urlencode($marca->get('nombre')) ?>",
    "direccion": "<?php echo urlencode($marca->get('nombre')) ?>",
    "telefono": "<?php echo urlencode($marca->get('telefono')) ?>",
    "email": "<?php echo urlencode($marca->get('email')) ?>",
    "web": "<?php echo urlencode($marca->get('web')) ?>",
    "observaciones": "<?php echo urlencode($marca->get('observaciones')) ?>"
  }<?php if ($i<count($values['list'])-1): ?>,<?php endif ?>
<?php endforeach ?>