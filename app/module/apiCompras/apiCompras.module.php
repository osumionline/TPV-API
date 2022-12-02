<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getPedidos', 'getPedidosGuardados', 'getPedidosRecepcionados', 'savePedido'],
	type: 'json',
	prefix: '/api-compras'
)]
class apiComprasModule {}
