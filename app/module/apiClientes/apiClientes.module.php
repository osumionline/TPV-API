<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['searchClientes', 'saveCliente', 'getEstadisticasCliente', 'getClientes', 'deleteCliente'],
	type: 'json',
	prefix: '/api-clientes'
)]
class apiClientesModule {}
