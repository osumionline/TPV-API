<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiVentas\AsignarTipoPago\AsignarTipoPagoComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\GetHistorico\GetHistoricoComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\GetLineasTicket\GetLineasTicketComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\GetLocalizadores\GetLocalizadoresComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\GetTicketImage\GetTicketImageComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\PrintTicket\PrintTicketComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\SaveVenta\SaveVentaComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\Search\SearchComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\SendTBai\SendTBaiComponent;
use Osumi\OsumiFramework\App\Module\ApiVentas\SendTicket\SendTicketComponent;

ORoute::prefix('/api-ventas', function() {
  ORoute::post('/asignar-tipo-pago',   AsignarTipoPagoComponent::class);
  ORoute::post('/get-historico',       GetHistoricoComponent::class);
  ORoute::post('/get-lineas-ticket',   GetLineasTicketComponent::class);
  ORoute::post('/get-localizadores',   GetLocalizadoresComponent::class);
  ORoute::get('/get-ticket-image/:id', GetTicketImageComponent::class);
  ORoute::post('/print-ticket',        PrintTicketComponent::class);
  ORoute::post('/save-venta',          SaveVentaComponent::class);
  ORoute::post('/search',              SearchComponent::class);
  ORoute::post('/send-tbai/:id',       SendTBaiComponent::class);
  ORoute::post('/send-ticket',         SendTicketComponent::class);
});
