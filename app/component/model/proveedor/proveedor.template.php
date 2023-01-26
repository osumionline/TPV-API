<?php
use OsumiFramework\App\Component\Model\ComercialListComponent;

if (is_null($values['proveedor'])){
?>
null
<?php
}
else { ?>
{
	"id": <?php echo $values['proveedor']->get('id') ?>,
	"nombre": "<?php echo urlencode($values['proveedor']->get('nombre')) ?>",
	"foto": <?php echo is_null($values['proveedor']->getFoto()) ? 'null' : '"'.$values['proveedor']->getFoto().'"' ?>,
	"direccion": <?php echo is_null($values['proveedor']->get('direccion')) ? 'null' : '"'.urlencode($values['proveedor']->get('direccion')).'"' ?>,
	"telefono": <?php echo is_null($values['proveedor']->get('telefono')) ? 'null' : '"'.urlencode($values['proveedor']->get('telefono')).'"' ?>,
	"email": <?php echo is_null($values['proveedor']->get('email')) ? 'null' : '"'.urlencode($values['proveedor']->get('email')).'"' ?>,
	"web": <?php echo is_null($values['proveedor']->get('web')) ? 'null' : '"'.urlencode($values['proveedor']->get('web')).'"' ?>,
	"observaciones": <?php echo is_null($values['proveedor']->get('observaciones')) ? 'null' : '"'.urlencode($values['proveedor']->get('observaciones')).'"' ?>,
	"marcas": [<?php echo implode(',', $values['proveedor']->getMarcasList()) ?>],
	"comerciales": [<?php echo new ComercialListComponent(['list' => $values['proveedor']->getComerciales()]) ?>]
}
<?php
}
?>
