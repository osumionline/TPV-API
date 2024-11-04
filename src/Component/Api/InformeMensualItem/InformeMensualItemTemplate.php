<?php if (is_null($item)): ?>
null
<?php else: ?>
  {
    "num": <?php echo $item['num'] ?>,
    "weekDay": "<?php echo urlencode($item['week_day']) ?>",
    "minTicket": <?php echo !is_null($item['min_ticket']) ? $item['min_ticket'] : 'null' ?>,
    "maxTicket": <?php echo !is_null($item['max_ticket']) ? $item['max_ticket'] : 'null' ?>,
    "efectivo": <?php echo $item['efectivo'] ?>,
    "otros": [
<?php foreach ($item['otros'] as $i => $otros_item): ?>
      {
        "nombre": "<?php echo urlencode($otros_item['nombre']) ?>",
        "valor": <?php echo $otros_item['valor'] ?>
      }<?php if ($i < count($item['otros']) - 1): ?>,<?php endif ?>
<?php endforeach ?>
    ],
    "totalDia": <?php echo $item['total_dia'] ?>,
    "suma": <?php echo $item['suma'] ?>
  }
<?php endif ?>
