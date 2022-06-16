<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: 'getCategorias',
	type: 'json',
	prefix: '/api-categorias'
)]
class apiCategoriasModule {}
