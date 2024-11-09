<?php

use Osumi\OsumiFramework\App\Component\Model\Cliente\ClienteComponent;
use Osumi\OsumiFramework\App\Component\Model\LineaReservaList\LineaReservaListComponent;
?>
<?php if (is_null($reserva)): ?>
	null
<?php else: ?>
	{
	"id": {{ reserva.id }},
	"idCliente": {{ reserva.id_cliente | number }},
	"cliente": <?php echo new ClienteComponent(['cliente' => $reserva->getCliente()]) ?>,
	"total": {{ reserva.total }},
	"fecha": {{ reserva.created_at | date("d/m/Y H:i") }},
	"lineas": [<?php echo new LineaReservaListComponent(['list' => $reserva->getLineas()]) ?>]
	}
<?php endif ?>
