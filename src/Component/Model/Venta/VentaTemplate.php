<?php

use Osumi\OsumiFramework\App\Component\Model\LineaVentaList\LineaVentaListComponent;
?>
<?php if (is_null($venta)): ?>
	null
<?php else: ?>
	{
	"id": {{ venta.id }},
	"editable": <?php echo $venta->getEditable() ? 'true' : 'false' ?>,
	"idEmpleado": {{ venta.id_empleado | number }},
	"idCliente": {{ venta.id_cliente | number }},
	"cliente": <?php echo is_null($venta->id_cliente) ? 'null' : '"' . urlencode($venta->getCliente()->nombre_apellidos) . '"' ?>,
	"total": {{ venta.total }},
	"entregado": {{ venta.entregado }},
	"pagoMixto": {{ venta.pago_mixto | bool }},
	"idTipoPago": {{ venta.id_tipo_pago | number }},
	"nombreTipoPago": "<?php echo urlencode($venta->getNombreTipoPago()) ?>",
	"entregadoOtro": {{ venta.entregado_otro | number }},
	"saldo": {{ venta.saldo | number }},
	"facturada": {{ venta.facturada | bool }},
	"statusFactura": "<?php echo $venta->getStatusFactura() ?>",
	"fecha": {{ venta.created_at | date("d/m/Y H:i") }},
	"lineas": [<?php echo new LineaVentaListComponent(['list' => $venta->getLineas()]) ?>]
	}
<?php endif ?>
