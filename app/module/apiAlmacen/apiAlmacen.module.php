<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getInventario', 'saveInventario', 'deleteInventario', 'saveAllInventario', 'exportInventario'],
	type: 'json',
	prefix: '/api-almacen'
)]
class apiAlmacenModule {}
