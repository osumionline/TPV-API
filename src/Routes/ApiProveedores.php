<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiProveedores\DeleteComercial\DeleteComercialComponent;
use Osumi\OsumiFramework\App\Module\ApiProveedores\DeleteProveedor\DeleteProveedorComponent;
use Osumi\OsumiFramework\App\Module\ApiProveedores\GetProveedores\GetProveedoresComponent;
use Osumi\OsumiFramework\App\Module\ApiProveedores\SaveComercial\SaveComercialComponent;
use Osumi\OsumiFramework\App\Module\ApiProveedores\SaveProveedor\SaveProveedorComponent;

ORoute::prefix('/api-proveedores', function() {
  ORoute::post('/delete-comercial', DeleteComercialComponent::class);
  ORoute::post('/delete-proveedor', DeleteProveedorComponent::class);
  ORoute::post('/get-proveedores', GetProveedoresComponent::class);
  ORoute::post('/save-comercial', SaveComercialComponent::class);
  ORoute::post('/save-proveedor', SaveProveedorComponent::class);
});
