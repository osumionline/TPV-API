<?php

use Osumi\OsumiFramework\App\Component\Model\LineaPedidoList\LineaPedidoListComponent;
use Osumi\OsumiFramework\App\Component\Model\PdfPedidoList\PdfPedidoListComponent;
use Osumi\OsumiFramework\App\Component\Model\VistaPedidoList\VistaPedidoListComponent;
?>
<?php if (is_null($pedido)): ?>
	null
<?php else: ?>
	{
	"id": {{ pedido.id }},
	"idProveedor": {{ pedido.id_proveedor | number }},
	"idMetodoPago": {{ pedido.metodo_pago | number }},
	"metodoPago": <?php echo is_null($pedido->getMetodoPago()) ? 'null' : '"' . urlencode($pedido->getMetodoPago()) . '"' ?>,
	"tipo": {{ pedido.tipo | string }},
	"num": {{ pedido.num | string }},
	"importe": {{ pedido.importe }},
	"portes": {{ pedido.portes }},
	"descuento": {{ pedido.descuento }},
	"fechaPago": {{ pedido.fecha_pago | date("d/m/Y") }},
	"fechaPedido": {{ pedido.fecha_pedido | date("d/m/Y") }},
	"fechaRecepcionado": {{ pedido.fecha_recepcionado | date("d/m/Y") }},
	"re": {{ pedido.re | bool }},
	"ue": {{ pedido.europeo | bool }},
	"faltas": {{ pedido.faltas | bool }},
	"recepcionado": {{ pedido.recepcionado | bool }},
	"observaciones": {{ pedido.observaciones | string }},
	"lineas": [<?php echo new LineaPedidoListComponent(['list' => $pedido->getLineas()) ?>],
	"pdfs": [<?php echo new PdfPedidoListComponent(['list' => $pedido->getPdfs()]) ?>],
	"vista": [<?php echo new VistaPedidoListComponent(['list' => $pedido->getVista()]) ?>]
	}
<?php endif ?>
