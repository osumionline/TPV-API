<?php

use Osumi\OsumiFramework\App\Component\Model\VentaList\VentaListComponent;
?>
<?php if (is_null($factura)): ?>
	null
<?php else: ?>
	{
	"id": {{ factura.id }},
	"idCliente": {{ factura.id_cliente }},
	"numFactura": {{ factura.num_factura | number }},
	"nombreApellidos": {{ factura.nombre_apellidos | string }},
	"dniCif": {{ factura.dni_cif | string }},
	"telefono": {{ factura.telefono | string }},
	"email": {{ factura.email | string }},
	"direccion": {{ factura.direccion | string }},
	"codigoPostal": {{ factura.codigo_postal | string }},
	"poblacion": {{ factura.poblacion | string }},
	"provincia": {{ factura.provincia | number }},
	"importe": {{ factura.importe }},
	"impresa": {{ factura.impresa | bool }},
	"fecha": {{ factura.created_at | date("d/m/Y H:i") }},
	"ventas": [<?php echo new VentaListComponent(['list' => $factura->getVentas('hideGifts')]) ?>]
	}
<?php endif ?>
