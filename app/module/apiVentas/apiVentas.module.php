<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['saveVenta', 'search', 'getHistorico', 'asignarTipoPago', 'sendTicket'],
	type: 'json',
	prefix: '/api-ventas'
)]
class apiVentasModule {}
