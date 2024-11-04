<?php

use Osumi\OsumiFramework\App\Component\Model\LineaVentaList\LineaVentaListComponent;
?>
<?php if (is_null($venta)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $venta->id ?>,
	"editable": <?php echo $venta->getEditable() ? 'true' : 'false' ?>,
	"idEmpleado": <?php echo is_null($venta->id_empleado) ? 'null' : $venta->id_empleado ?>,
	"idCliente": <?php echo is_null($venta->id_cliente) ? 'null' : $venta->id_cliente ?>,
	"cliente": <?php echo is_null($venta->id_cliente) ? 'null' : '"' . urlencode($venta->getCliente()->nombre_apellidos) . '"' ?>,
	"total": <?php echo $venta->total ?>,
	"entregado": <?php echo $venta->entregado ?>,
	"pagoMixto": <?php echo $venta->pago_mixto ? 'true' : 'false' ?>,
	"idTipoPago": <?php echo is_null($venta->id_tipo_pago) ? 'null' : $venta->id_tipo_pago ?>,
	"nombreTipoPago": "<?php echo urlencode($venta->getNombreTipoPago()) ?>",
	"entregadoOtro": <?php echo is_null($venta->entregado_otro) ? 'null' : $venta->entregado_otro ?>,
	"saldo": <?php echo is_null($venta->saldo) ? 'null' : $venta->saldo ?>,
	"facturada": <?php echo $venta->facturada ? 'true' : 'false' ?>,
	"statusFactura": "<?php echo $venta->getStatusFactura() ?>",
	"fecha": "<?php echo $venta->get('created_at', 'd/m/Y H:i') ?>",
	"lineas": [<?php echo new LineaVentaListComponent(['list' => $venta->getLineas()]) ?>]
	}
<?php endif ?>
