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
	"proveedor": <?php echo is_null($pedido->id_proveedor) ? 'null' : '"' . urlencode($pedido->getProveedor()->nombre) . '"' ?>,
	"idMetodoPago": {{ pedido.metodo_pago | number }},
	"metodoPago": <?php echo is_null($pedido->getMetodoPago()) ? 'null' : '"' . urlencode($pedido->getMetodoPago()) . '"' ?>,
	"re": {{ pedido.re | bool }},
	"ue": {{ pedido.europeo | bool }},
	"tipo": {{ pedido.tipo | string }},
	"num": {{ pedido.num | string }},
	"fechaPago": {{ pedido.fecha_pago | date("d/m/Y") }},
	"fechaPedido": {{ pedido.fecha_pedido | date("d/m/Y") }},
	"fechaRecepcionado": {{ pedido.fecha_recepcionado | date("d/m/Y") }},
	"lineas": [<?php echo new LineaPedidoListComponent(['list' => $pedido->getLineas()]) ?>],
	"importe": {{ pedido.importe }},
	"portes": {{ pedido.portes }},
	"descuento": {{ pedido.descuento }},
	"faltas": {{ pedido.faltas | bool }},
	"recepcionado": {{ pedido.recepcionado | bool }},
	"observaciones": {{ pedido.observaciones | string }},
	"pdfs": [<?php echo new PdfPedidoListComponent(['list' => $pedido->getPdfs()]) ?>],
	"vista": [<?php echo new VistaPedidoListComponent(['list' => $pedido->getVista()]) ?>]
	}
<?php endif ?>
