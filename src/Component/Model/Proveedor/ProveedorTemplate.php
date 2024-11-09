<?php

use Osumi\OsumiFramework\App\Component\Model\ComercialList\ComercialListComponent;
?>
<?php if (is_null($proveedor)): ?>
	null
<?php else: ?>
	{
	"id": {{ proveedor.id }},
	"nombre": {{ proveedor.nombre | string }},
	"foto": <?php echo is_null($proveedor->getFoto()) ? 'null' : '"' . $proveedor->getFoto() . '"' ?>,
	"direccion": {{ proveedor.direccion | string }},
	"telefono": {{ proveedor.telefono | string }},
	"email": {{ proveedor.email | string }},
	"web": {{ proveedor.web | string }},
	"observaciones": {{ proveedor.observaciones | string }},
	"marcas": [<?php echo implode(',', $proveedor->getMarcasList()) ?>],
	"comerciales": [<?php echo new ComercialListComponent(['list' => $proveedor->getComerciales()]) ?>]
	}
<?php endif ?>
