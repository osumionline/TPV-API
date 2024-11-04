<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiCategorias\GetCategorias\GetCategoriasComponent;

ORoute::prefix('/api-categorias', function() {
  ORoute::post('/get-categorias', GetCategoriasComponent::class);
});
