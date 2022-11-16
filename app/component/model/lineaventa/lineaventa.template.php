<?php if (is_null($values['lineaventa'])): ?>
null
<?php else: ?>
{
	"id": <?php echo $values['lineaventa']->get('id') ?>,
	"idArticulo": <?php echo $values['lineaventa']->get('id_articulo') ?>,
	"articulo": "<?php echo urlencode($values['lineaventa']->getArticulo()->get('nombre')) ?>",
	"localizador": <?php echo $values['lineaventa']->getArticulo()->get('localizador') ?>,
	"marca": "<?php echo urlencode($values['lineaventa']->getArticulo()->getMarca()->get('nombre')) ?>",
	"puc": <?php echo $values['lineaventa']->get('puc') ?>,
	"pvp": <?php echo $values['lineaventa']->get('pvp') ?>,
	"iva": <?php echo $values['lineaventa']->get('iva') ?>,
	"re": <?php echo $values['lineaventa']->get('re') ?>,
	"importe": <?php echo $values['lineaventa']->get('importe') ?>,
	"descuento": <?php echo is_null($values['lineaventa']->get('descuento')) ? 'null' : $values['lineaventa']->get('descuento') ?>,
	"importeDescuento": <?php echo is_null($values['lineaventa']->get('importe_descuento')) ? 'null' : $values['lineaventa']->get('importe_descuento') ?>,
	"devuelto": <?php echo $values['lineaventa']->get('devuelto') ?>,
	"unidades": <?php echo $values['lineaventa']->get('unidades') ?>
}
<?php endif ?>
