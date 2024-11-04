<?php if (is_null($tipopago)): ?>
null
<?php else: ?>
{
	"id": <?php echo $tipopago->id ?>,
	"nombre": "<?php echo urlencode($tipopago->nombre) ?>",
	"foto": "<?php echo $tipopago->getFoto() ?>",
	"afectaCaja": <?php echo $tipopago->afecta_caja ? 'true' : 'false' ?>,
	"orden": <?php echo $tipopago->orden ?>,
	"fisico": <?php echo $tipopago->fisico ? 'true' : 'false' ?>
}
<?php endif ?>
