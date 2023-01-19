<?php if (is_null($values['item'])): ?>
null
<?php else: ?>
  {
    "num": <?php echo $values['item']['num'] ?>,
    "weekDay": "<?php echo urlencode($values['item']['week_day']) ?>",
    "minTicket": <?php echo !is_null($values['item']['min_ticket']) ? $values['item']['min_ticket'] : 'null' ?>,
    "maxTicket": <?php echo !is_null($values['item']['max_ticket']) ? $values['item']['max_ticket'] : 'null' ?>,
    "efectivo": <?php echo $values['item']['efectivo'] ?>,
    "otros": [
<?php foreach ($values['item']['otros'] as $i => $item): ?>
      {
        "nombre": "<?php echo urlencode($item['nombre']) ?>",
        "valor": <?php echo $item['valor'] ?>
      }<?php if ($i<count($values['item']['otros'])-1): ?>,<?php endif ?>
<?php endforeach ?>
    ],
    "totalDia": <?php echo $values['item']['total_dia'] ?>,
    "suma": <?php echo $values['item']['suma'] ?>
  }
<?php endif ?>
