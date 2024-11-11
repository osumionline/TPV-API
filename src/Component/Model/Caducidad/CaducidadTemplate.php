<?php
use Osumi\OsumiFramework\App\Component\Model\Articulo\ArticuloComponent;
?>
<?php if (is_null($caducidad)): ?>
null
<?php else: ?>
{
	"id": {{ caducidad.id }},
	"articulo": <?php echo new ArticuloComponent(['articulo' => $caducidad->getArticulo()]) ?>,
	"unidades": {{ caducidad.unidades }},
	"puc": {{ caducidad.puc | number }},
	"pvp": {{caducidad.pvp | number }},
	"createdAt": {{ caducidad.created_at | date }}
}
<?php endif ?>
