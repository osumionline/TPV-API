<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiClientes\AsignarCliente\AsignarClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\DeleteCliente\DeleteClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\DeleteFactura\DeleteFacturaComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\DeleteLineaReserva\DeleteLineaReservaComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\DeleteReserva\DeleteReservaComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetCliente\GetClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetClientes\GetClientesComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetEstadisticasCliente\GetEstadisticasClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetFacturaCliente\GetFacturaClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetFacturasCliente\GetFacturasClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetReservas\GetReservasComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\GetVentasCliente\GetVentasClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SaveCliente\SaveClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SaveFactura\SaveFacturaComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SaveFacturaFromVenta\SaveFacturaFromVentaComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SaveReserva\SaveReservaComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SearchClientes\SearchClientesComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SearchVentasCliente\SearchVentasClienteComponent;
use Osumi\OsumiFramework\App\Module\ApiClientes\SendFactura\SendFacturaComponent;

ORoute::prefix('/api-clientes', function() {
  ORoute::post('/asignar-cliente',          AsignarClienteComponent::class);
  ORoute::post('/delete-cliente',           DeleteClienteComponent::class);
  ORoute::post('/delete-factura',           DeleteFacturaComponent::class);
  ORoute::post('/delete-linea-reserva',     DeleteLineaReservaComponent::class);
  ORoute::post('/delete-reserva',           DeleteReservaComponent::class);
  ORoute::post('/get-cliente',              GetClienteComponent::class);
  ORoute::post('/get-clientes',             GetClientesComponent::class);
  ORoute::post('/get-estadisticas-cliente', GetEstadisticasClienteComponent::class);
  ORoute::post('/get-factura-cliente',      GetFacturaClienteComponent::class);
  ORoute::post('/get-facturas-cliente',     GetFacturasClienteComponent::class);
  ORoute::post('/get-reservas',             GetReservasComponent::class);
  ORoute::post('/get-ventas-cliente',       GetVentasClienteComponent::class);
  ORoute::post('/save-cliente',             SaveClienteComponent::class);
  ORoute::post('/save-factura',             SaveFacturaComponent::class);
  ORoute::post('/save-factura-from-venta',  SaveFacturaFromVentaComponent::class);
  ORoute::post('/save-reserva',             SaveReservaComponent::class);
  ORoute::post('/search-clientes',          SearchClientesComponent::class);
  ORoute::post('/search-ventas-cliente',    SearchVentasClienteComponent::class);
  ORoute::post('/send-factura',             SendFacturaComponent::class);
});
