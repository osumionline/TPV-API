<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getInformeCierreCajaMensual', 'getInformeMensual'],
	type: 'json',
	prefix: '/api-informes'
)]
class apiInformesModule {}
