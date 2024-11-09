<?php if (is_null($pagocaja)): ?>
null
<?php else: ?>
{
	"id": {{ pagocaja.id }},
	"concepto": {{ pagocaja.concepto | string }},
	"descripcion": {{ pagocaja.descripcion | string }},
	"importe": {{ pagocaja.importe }},
	"fecha": {{ pagocaja.created_at | date("d/m/Y H:i") }},
	"editable": <?php echo $pagocaja->getEditable() ? 'true' : 'false' ?>
}
<?php endif ?>
