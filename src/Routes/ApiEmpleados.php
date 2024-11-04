<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiEmpleados\DeleteEmpleado\DeleteEmpleadoComponent;
use Osumi\OsumiFramework\App\Module\ApiEmpleados\GetEmpleados\GetEmpleadosComponent;
use Osumi\OsumiFramework\App\Module\ApiEmpleados\Login\LoginComponent;
use Osumi\OsumiFramework\App\Module\ApiEmpleados\SaveEmpleado\SaveEmpleadoComponent;

ORoute::prefix('/api-empleados', function() {
  ORoute::post('/delete-empleado', DeleteEmpleadoComponent::class);
  ORoute::post('/get-empleados',   GetEmpleadosComponent::class);
  ORoute::post('/login',           LoginComponent::class);
  ORoute::post('/save-empleado',   SaveEmpleadoComponent::class);
});
