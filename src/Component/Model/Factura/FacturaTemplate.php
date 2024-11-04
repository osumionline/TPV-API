<?php

use Osumi\OsumiFramework\App\Component\Model\VentaList\VentaListComponent;
?>
<?php if (is_null($factura)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $factura->id ?>,
	"idCliente": <?php echo $factura->id_cliente ?>,
	"numFactura": <?php echo is_null($factura->num_factura) ? 'null' : $factura->num_factura ?>,
	"nombreApellidos": "<?php echo urlencode($factura->nombre_apellidos) ?>",
	"dniCif": <?php echo is_null($factura->dni_cif) ? 'null' : '"' . urlencode($factura->dni_cif) . '"' ?>,
	"telefono": <?php echo is_null($factura->telefono) ? 'null' : '"' . urlencode($factura->telefono) . '"' ?>,
	"email": <?php echo is_null($factura->email) ? 'null' : '"' . urlencode($factura->email) . '"' ?>,
	"direccion": <?php echo is_null($factura->direccion) ? 'null' : '"' . urlencode($factura->direccion) . '"' ?>,
	"codigoPostal": <?php echo is_null($factura->codigo_postal) ? 'null' : '"' . urlencode($factura->codigo_postal) . '"' ?>,
	"poblacion": <?php echo is_null($factura->poblacion) ? 'null' : '"' . urlencode($factura->poblacion) . '"' ?>,
	"provincia": <?php echo is_null($factura->provincia) ? 'null' : $factura->provincia ?>,
	"importe": <?php echo $factura->importe ?>,
	"impresa": <?php echo $factura->impresa ? 'true' : 'false' ?>,
	"fecha": "<?php echo $factura->get('created_at', 'd/m/Y H:i') ?>",
	"ventas": [<?php echo new VentaListComponent(['list' => $factura->getVentas('hideGifts')]) ?>]
	}
<?php endif ?>
