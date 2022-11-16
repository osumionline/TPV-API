<?php
use OsumiFramework\App\Component\Model\LineaventaListComponent;

if (is_null($values['venta'])) { ?>
null
<?php
}
else { ?>
{
	"id": <?php echo $values['venta']->get('id') ?>,
	"idEmpleado": <?php echo is_null($values['venta']->get('id_empleado')) ? 'null' : $values['venta']->get('id_empleado') ?>,
	"idCliente": <?php echo is_null($values['venta']->get('id_cliente')) ? 'null' : $values['venta']->get('id_cliente') ?>,
	"cliente": <?php echo is_null($values['venta']->get('id_cliente')) ? 'null' : '"'.urlencode($values['venta']->getCliente()->get('nombre_apellidos')).'"' ?>,
	"total": <?php echo $values['venta']->get('total') ?>,
	"entregado": <?php echo $values['venta']->get('entregado') ?>,
	"pagoMixto": <?php echo $values['venta']->get('pago_mixto') ? 'true' : 'false' ?>,
	"idTipoPago": <?php echo is_null($values['venta']->get('id_tipo_pago')) ? 'null' : $values['venta']->get('id_tipo_pago') ?>,
	"nombreTipoPago": "<?php echo urlencode($values['venta']->getNombreTipoPago()) ?>",
	"entregadoOtro": <?php echo is_null($values['venta']->get('entregado_otro')) ? 'null' : $values['venta']->get('entregado_otro') ?>,
	"saldo": <?php echo is_null($values['venta']->get('saldo')) ? 'null' : $values['venta']->get('saldo') ?>,
	"fecha": "<?php echo $values['venta']->get('created_at', 'd/m/Y H:i') ?>",
	"lineas": [<?php echo new LineaventaListComponent(['list' => $values['venta']->getLineas()]) ?>]
}
<?php
}
?>
