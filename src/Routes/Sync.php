<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Sync\SyncSale\SyncSaleComponent;
use Osumi\OsumiFramework\App\Module\Sync\SyncStock\SyncStockComponent;

ORoute::prefix('/sync', function() {
  ORoute::post('/sale', SyncSaleComponent::class);
  ORoute::post('/stock', SyncStockComponent::class);
});
