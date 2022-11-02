<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getEmpleados', 'login', 'saveEmpleado', 'deleteEmpleado'],
	type: 'json',
	prefix: '/api-empleados'
)]
class apiEmpleadosModule {}
