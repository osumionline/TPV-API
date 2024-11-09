<?php if (is_null($tipopago)): ?>
null
<?php else: ?>
{
	"id": {{ tipopago.id }},
	"nombre": {{ tipopago.nombre | string }},
	"foto": "<?php echo $tipopago->getFoto() ?>",
	"afectaCaja": {{ tipopago.afecta_caja | bool }},
	"orden": {{ tipopago.orden }},
	"fisico": {{ tipopago.fisico | bool }}
}
<?php endif ?>
