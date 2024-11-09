<?php

use Osumi\OsumiFramework\App\Component\Model\CodigoBarrasList\CodigoBarrasListComponent;
use Osumi\OsumiFramework\App\Component\Model\EtiquetaList\EtiquetaListComponent;
use Osumi\OsumiFramework\App\Component\Model\EtiquetaWebList\EtiquetaWebListComponent;
?>
<?php if (is_null($articulo)): ?>
	null
<?php else: ?>
	{
	"id": {{ articulo.id }},
	"localizador": {{ articulo.localizador }},
	"nombre": {{ articulo.nombre | string }},
	"idCategoria": {{ articulo.id_categoria | number }},
	"idMarca": {{ articulo.id_marca }},
	"idProveedor": {{ articulo.id_proveedor | number }},
	"referencia": {{ articulo.referencia | string }},
	"palb": {{ articulo.palb }},
	"puc": {{ articulo.puc }},
	"pvp": {{ articulo.pvp }},
	"pvpDescuento": {{ articulo.pvp_descuento | number }},
	"iva": {{ articulo.iva }},
	"re": {{ articulo.re }},
	"margen": {{ articulo.margen }},
	"margenDescuento": {{ articulo.margen_descuento | number }},
	"stock": {{ articulo.stock}},
	"stockMin": {{ articulo.stock_min }},
	"stockMax": {{ articulo.stock_max }},
	"loteOptimo": {{ articulo.lote_optimo }},
	"ventaOnline": {{ articulo.venta_online | bool }},
	"fechaCaducidad": {{ articulo.fecha_caducidad | date }},
	"mostrarEnWeb": {{ articulo.mostrar_en_web | bool }},
	"descCorta": {{ articulo.desc_corta | string }},
	"descripcion": {{ articulo.descripcion | string }},
	"observaciones": {{ articulo.observaciones | string }},
	"mostrarObsPedidos": {{ articulo.mostrar_obs_pedidos | bool }},
	"mostrarObsVentas": {{ articulo.mostrar_obs_ventas | bool }},
	"accesoDirecto": {{ articulo.acceso_directo | number }},
	"codigosBarras": [<?php echo new CodigoBarrasListComponent(['list' => $articulo->getCodigosBarras()]) ?>],
	"fotos": [<?php echo implode(',', $articulo->getFotosList()) ?>],
	"etiquetas": [<?php echo new EtiquetaListComponent(['list' => $articulo->getEtiquetas()]) ?>],
	"etiquetasWeb": [<?php echo new EtiquetaWebListComponent(['list' => $articulo->getEtiquetasWeb()]) ?>]
	}
<?php endif ?>
