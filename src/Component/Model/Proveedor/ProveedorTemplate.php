<?php

use Osumi\OsumiFramework\App\Component\Model\ComercialList\ComercialListComponent;
?>
<?php if (is_null($proveedor)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $proveedor->id ?>,
	"nombre": "<?php echo urlencode($proveedor->nombre) ?>",
	"foto": <?php echo is_null($proveedor->getFoto()) ? 'null' : '"' . $proveedor->getFoto() . '"' ?>,
	"direccion": <?php echo is_null($proveedor->direccion) ? 'null' : '"' . urlencode($proveedor->direccion) . '"' ?>,
	"telefono": <?php echo is_null($proveedor->telefono) ? 'null' : '"' . urlencode($proveedor->telefono) . '"' ?>,
	"email": <?php echo is_null($proveedor->email) ? 'null' : '"' . urlencode($proveedor->email) . '"' ?>,
	"web": <?php echo is_null($proveedor->web) ? 'null' : '"' . urlencode($proveedor->web) . '"' ?>,
	"observaciones": <?php echo is_null($proveedor->observaciones) ? 'null' : '"' . urlencode($proveedor->observaciones) . '"' ?>,
	"marcas": [<?php echo implode(',', $proveedor->getMarcasList()) ?>],
	"comerciales": [<?php echo new ComercialListComponent(['list' => $proveedor->getComerciales()]) ?>]
	}
<?php endif ?>
