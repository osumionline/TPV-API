<?php

use Osumi\OsumiFramework\App\Component\Model\Cliente\ClienteComponent;
use Osumi\OsumiFramework\App\Component\Model\LineaReservaList\LineaReservaListComponent;
?>
<?php if (is_null($reserva)): ?>
	null
<?php else: ?>
	{
	"id": <?php echo $reserva->id ?>,
	"idCliente": <?php echo is_null($reserva->id_cliente) ? 'null' : $reserva->id_cliente ?>,
	"cliente": <?php echo new ClienteComponent(['cliente' => $reserva->getCliente()]) ?>,
	"total": <?php echo $reserva->total ?>,
	"fecha": "<?php echo $reserva->get('created_at', 'd/m/Y H:i') ?>",
	"lineas": [<?php echo new LineaReservaListComponent(['list' => $reserva->getLineas()]) ?>]
	}
<?php endif ?>
