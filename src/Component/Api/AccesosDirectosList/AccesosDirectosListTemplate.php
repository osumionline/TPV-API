<?php if (count($list) > 0): ?>
  [
<?php foreach ($list as $i => $item): ?>
			{
				"id": <?php echo $item['id'] ?>,
				"accesoDirecto": <?php echo $item['acceso_directo'] ?>,
				"nombre": "<?php echo urlencode($item['nombre']) ?>"
			}<?php if ($i < count($list) - 1): ?>,<?php endif ?>
<?php endforeach ?>
		]
<?php else: ?>
	[]
<?php endif ?>
