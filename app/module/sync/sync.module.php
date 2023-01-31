<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['syncSale', 'syncStock'],
	type: 'json',
	prefix: '/sync'
)]
class syncModule {}
