<?php
use OsumiFramework\App\Component\Model\LineaReservaListComponent;

if (is_null($values['reserva'])) {
?>
null
<?php
}
else { ?>
{
	"id": <?php echo $values['reserva']->get('id') ?>,
	"idCliente": <?php echo is_null($values['reserva']->get('id_cliente')) ? 'null' : $values['reserva']->get('id_cliente') ?>,
	"cliente": <?php echo is_null($values['reserva']->get('id_cliente')) ? 'null' : '"'.urlencode($values['reserva']->getCliente()->getNombre()).'"' ?>,
	"total": <?php echo $values['reserva']->get('total') ?>,
	"fecha": "<?php echo $values['reserva']->get('created_at', 'd/m/Y H:i') ?>",
	"lineas": [<?php echo new LineaReservaListComponent(['list' => $values['reserva']->getLineas()]) ?>]
}
<?php
}
?>
