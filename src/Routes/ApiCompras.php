<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiCompras\AutoSavePedido\AutoSavePedidoComponent;
use Osumi\OsumiFramework\App\Module\ApiCompras\DeletePedido\DeletePedidoComponent;
use Osumi\OsumiFramework\App\Module\ApiCompras\GetPedido\GetPedidoComponent;
use Osumi\OsumiFramework\App\Module\ApiCompras\GetPedidos\GetPedidosComponent;
use Osumi\OsumiFramework\App\Module\ApiCompras\GetPedidosGuardados\GetPedidosGuardadosComponent;
use Osumi\OsumiFramework\App\Module\ApiCompras\GetPedidosRecepcionados\GetPedidosRecepcionadosComponent;
use Osumi\OsumiFramework\App\Module\ApiCompras\SavePedido\SavePedidoComponent;

ORoute::prefix('/api-compras', function() {
  ORoute::post('/auto-save-pedido',          AutoSavePedidoComponent::class);
  ORoute::post('/delete-pedido',             DeletePedidoComponent::class);
  ORoute::post('/get-pedido',                GetPedidoComponent::class);
  ORoute::post('/get-pedidos',               GetPedidosComponent::class);
  ORoute::post('/get-pedidos-guardados',     GetPedidosGuardadosComponent::class);
  ORoute::post('/get-pedidos-recepcionados', GetPedidosRecepcionadosComponent::class);
  ORoute::post('/save-pedido',               SavePedidoComponent::class);
});
