<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['saveVenta', 'getVenta'],
	type: 'json',
	prefix: '/api-ventas'
)]
class apiVentasModule {}
