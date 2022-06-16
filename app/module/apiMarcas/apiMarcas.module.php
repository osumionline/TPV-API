<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: 'getMarcas, saveMarca',
	type: 'json',
	prefix: '/api-marcas'
)]
class apiMarcasModule {}
