<?php if (is_null($status)): ?>
null
<?php else: ?>
{
  "status": "<?php echo $status['status'] ?>",
  "id": <?php echo $status['id'] ?>,
  "message": "<?php echo urlencode($status['message']) ?>"
}
<?php endif ?>
