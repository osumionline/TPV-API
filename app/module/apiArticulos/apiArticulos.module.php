<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getStatistics', 'deleteArticulo', 'saveArticulo', 'loadArticulo', 'searchArticulos'],
	type: 'json',
	prefix: '/api-articulos'
)]
class apiArticulosModule {}
