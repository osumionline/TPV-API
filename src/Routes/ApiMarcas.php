<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiMarcas\DeleteMarca\DeleteMarcaComponent;
use Osumi\OsumiFramework\App\Module\ApiMarcas\GetMarcas\GetMarcasComponent;
use Osumi\OsumiFramework\App\Module\ApiMarcas\SaveMarca\SaveMarcaComponent;

ORoute::prefix('/api-marcas', function() {
  ORoute::post('/delete-marca', DeleteMarcaComponent::class);
  ORoute::post('/get-marcas',   GetMarcasComponent::class);
  ORoute::post('/save-marca',   SaveMarcaComponent::class);
});
