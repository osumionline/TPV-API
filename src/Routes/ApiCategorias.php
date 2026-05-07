<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiCategorias\GetCategorias\GetCategoriasComponent;
use Osumi\OsumiFramework\App\Module\ApiCategorias\GetArticulosCategoria\GetArticulosCategoriaComponent;
use Osumi\OsumiFramework\App\Module\ApiCategorias\SaveArticulosCategoria\SaveArticulosCategoriaComponent;
use Osumi\OsumiFramework\App\Module\ApiCategorias\SaveCategoria\SaveCategoriaComponent;
use Osumi\OsumiFramework\App\Module\ApiCategorias\DeleteCategoria\DeleteCategoriaComponent;

ORoute::prefix('/api-categorias', function() {
  ORoute::post('/get-categorias',           GetCategoriasComponent::class);
  ORoute::post('/get-articulos-categoria',  GetArticulosCategoriaComponent::class);
  ORoute::post('/save-articulos-categoria', SaveArticulosCategoriaComponent::class);
  ORoute::post('/save-categoria',           SaveCategoriaComponent::class);
  ORoute::post('/delete-categoria',         DeleteCategoriaComponent::class);
});
