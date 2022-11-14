<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getProveedores', 'saveProveedor', 'saveComercial', 'deleteComercial', 'deleteProveedor'],
	type: 'json',
	prefix: '/api-proveedores'
)]
class apiProveedoresModule {}
