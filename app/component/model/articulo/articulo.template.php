<?php if (is_null($values['articulo'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['articulo']->get('id') ?>,
	"localizador": <?php echo $values['articulo']->get('localizador') ?>,
	"nombre": "<?php echo urlencode($values['articulo']->get('nombre')) ?>",
	"idCategoria": <?php echo is_null($values['articulo']->get('id_categoria')) ? 'null' : $values['articulo']->get('id_categoria') ?>,
	"idMarca": <?php echo is_null($values['articulo']->get('id_marca')) ? 'null' : $values['articulo']->get('id_marca') ?>,
	"idProveedor": <?php echo is_null($values['articulo']->get('id_proveedor')) ? 'null' : $values['articulo']->get('id_proveedor') ?>,
	"referencia": "<?php echo is_null($values['articulo']->get('referencia')) ? 'null' : urlencode($values['articulo']->get('referencia')) ?>",
	"palb": <?php echo $values['articulo']->get('palb') ?>,
	"puc": <?php echo $values['articulo']->get('puc') ?>,
	"pvp": <?php echo $values['articulo']->get('pvp') ?>,
	"iva": <?php echo $values['articulo']->get('iva') ?>,
	"re": <?php echo $values['articulo']->get('re') ?>,
	"margen": <?php echo $values['articulo']->get('margen') ?>,
	"stock": <?php echo $values['articulo']->get('stock') ?>,
	"stockMin": <?php echo $values['articulo']->get('stock_min') ?>,
	"stockMax": <?php echo $values['articulo']->get('stock_max') ?>,
	"loteOptimo": <?php echo $values['articulo']->get('lote_optimo') ?>,
	"ventaOnline": <?php echo $values['articulo']->get('venta_online') ? 'true' : 'false' ?>,
	"fechaCaducidad": <?php echo is_null($values['articulo']->get('fecha_caducidad')) ? 'null' : '"'.$values['articulo']->get('fecha_caducidad', 'm-y').'"' ?>,
	"mostrarEnWeb": <?php echo $values['articulo']->get('mostrar_en_web') ? 'true' : 'false' ?>,
	"descCorta": "<?php echo is_null($values['articulo']->get('desc_corta')) ? 'null' : urlencode($values['articulo']->get('desc_corta')) ?>",
	"descripcion": "<?php echo is_null($values['articulo']->get('descripcion')) ? 'null' : urlencode($values['articulo']->get('descripcion')) ?>",
	"observaciones": "<?php echo is_null($values['articulo']->get('observaciones')) ? 'null' : urlencode($values['articulo']->get('observaciones')) ?>",
	"mostrarObsPedidos": <?php echo $values['articulo']->get('mostrar_obs_pedidos') ? 'true' : 'false' ?>,
	"mostrarObsVentas": <?php echo $values['articulo']->get('mostrar_obs_ventas') ? 'true' : 'false' ?>,
	"codigosBarras": [
	<?php foreach ($values['articulo']->getCodigosBarras() as $i => $cod_barras): ?>
			{
				"id": <?php echo $cod_barras->get('id') ?>,
				"codigoBarras": <?php echo $cod_barras->get('codigo_barras') ?>,
				"porDefecto": <?php echo $cod_barras->get('por_defecto') ? 'true' : 'false' ?>
			}<?php if ($i < count($values['articulo']->getCodigosBarras()) -1): ?>,<?php endif ?>
	<?php endforeach ?>
	],
	"fotos": [<?php echo implode(',', $values['articulo']->getFotosList()) ?>]
}
<?php endif ?>
