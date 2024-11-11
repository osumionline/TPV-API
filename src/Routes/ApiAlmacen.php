<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\AddCaducidad\AddCaducidadComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\DeleteCaducidad\DeleteCaducidadComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\DeleteInventario\DeleteInventarioComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\ExportInventario\ExportInventarioComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\GetCaducidades\GetCaducidadesComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\GetInventario\GetInventarioComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\SaveAllInventario\SaveAllInventarioComponent;
use Osumi\OsumiFramework\App\Module\ApiAlmacen\SaveInventario\SaveInventarioComponent;

ORoute::prefix('/api-almacen', function() {
  ORoute::post('/add-caducidad',       AddCaducidadComponent::class);
  ORoute::post('/delete-caducidad',    DeleteCaducidadComponent::class);
  ORoute::post('/delete-inventario',   DeleteInventarioComponent::class);
  ORoute::post('/export-inventario',   ExportInventarioComponent::class);
  ORoute::post('/get-caducidades',     GetCaducidadesComponent::class);
  ORoute::post('/get-inventario',      GetInventarioComponent::class);
  ORoute::post('/save-all-inventario', SaveAllInventarioComponent::class);
  ORoute::post('/save-inventario',     SaveInventarioComponent::class);
});
