<?php

use Osumi\OsumiFramework\App\Component\Model\CodigoBarrasList\CodigoBarrasListComponent;
use Osumi\OsumiFramework\App\Component\Model\EtiquetaList\EtiquetaListComponent;
use Osumi\OsumiFramework\App\Component\Model\EtiquetaWebList\EtiquetaWebListComponent;
?>
<?php if (is_null($articulo)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $articulo->id ?>,
	"localizador": <?php echo $articulo->localizador ?>,
	"nombre": "<?php echo urlencode($articulo->nombre) ?>",
	"idCategoria": <?php echo is_null($articulo->id_categoria) ? 'null' : $articulo->id_categoria ?>,
	"idMarca": <?php echo $articulo->id_marca ?>,
	"idProveedor": <?php echo is_null($articulo->id_proveedor) ? 'null' : $articulo->id_proveedor ?>,
	"referencia": <?php echo is_null($articulo->referencia) ? 'null' : '"' . urlencode($articulo->referencia) . '"' ?>,
	"palb": <?php echo $articulo->palb ?>,
	"puc": <?php echo $articulo->puc ?>,
	"pvp": <?php echo $articulo->pvp ?>,
	"pvpDescuento": <?php echo is_null($articulo->pvp_descuento) ? 'null' : $articulo->pvp_descuento ?>,
	"iva": <?php echo $articulo->iva ?>,
	"re": <?php echo $articulo->re ?>,
	"margen": <?php echo $articulo->margen ?>,
	"margenDescuento": <?php echo is_null($articulo->margen_descuento) ? 'null' : $articulo->margen_descuento ?>,
	"stock": <?php echo $articulo->stock ?>,
	"stockMin": <?php echo $articulo->stock_min ?>,
	"stockMax": <?php echo $articulo->stock_max ?>,
	"loteOptimo": <?php echo $articulo->lote_optimo ?>,
	"ventaOnline": <?php echo $articulo->venta_online ? 'true' : 'false' ?>,
	"fechaCaducidad": <?php echo is_null($articulo->fecha_caducidad) ? 'null' : '"'.$articulo->get('fecha_caducidad', 'd/m/Y H:i:s').'"' ?>,
	"mostrarEnWeb": <?php echo $articulo->mostrar_en_web ? 'true' : 'false' ?>,
	"descCorta": <?php echo is_null($articulo->desc_corta) ? 'null' : '"' . urlencode($articulo->desc_corta) . '"' ?>,
	"descripcion": <?php echo is_null($articulo->descripcion) ? 'null' : '"' . urlencode($articulo->descripcion) . '"' ?>,
	"observaciones": <?php echo is_null($articulo->observaciones) ? 'null' : '"' . urlencode($articulo->observaciones) . '"' ?>,
	"mostrarObsPedidos": <?php echo $articulo->mostrar_obs_pedidos ? 'true' : 'false' ?>,
	"mostrarObsVentas": <?php echo $articulo->mostrar_obs_ventas ? 'true' : 'false' ?>,
	"accesoDirecto": <?php echo is_null($articulo->acceso_directo) ? 'null' : $articulo->acceso_directo ?>,
	"codigosBarras": [<?php echo new CodigoBarrasListComponent(['list' => $articulo->getCodigosBarras()]) ?>],
	"fotos": [<?php echo implode(',', $articulo->getFotosList()) ?>],
	"etiquetas": [<?php echo new EtiquetaListComponent(['list' => $articulo->getEtiquetas()]) ?>],
	"etiquetasWeb": [<?php echo new EtiquetaWebListComponent(['list' => $articulo->getEtiquetasWeb()]) ?>]
	}
<?php endif ?>
