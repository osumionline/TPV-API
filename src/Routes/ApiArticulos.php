<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiArticulos\AsignarAccesoDirecto\AsignarAccesoDirectoComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\DeleteAccesoDirecto\DeleteAccesoDirectoComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\DeleteArticulo\DeleteArticuloComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\GetAccesosDirectos\GetAccesosDirectosComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\GetHistoricoArticulo\GetHistoricoArticuloComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\GetStatistics\GetStatisticsComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\LoadArticulo\LoadArticuloComponent;
use Osumi\OsumiFramework\App\Module\ApiArticulos\SaveArticulo\SaveArticuloComponent;

ORoute::prefix('/api-articulos', function() {
  ORoute::post('/asignar-acceso-directo', AsignarAccesoDirectoComponent::class);
  ORoute::post('/delete-acceso-directo',  DeleteAccesoDirectoComponent::class);
  ORoute::post('/delete-articulo',        DeleteArticuloComponent::class);
  ORoute::post('/get-accesos-directos',   GetAccesosDirectosComponent::class);
  ORoute::post('/get-historico-articulo', GetHistoricoArticuloComponent::class);
  ORoute::post('/get-statistics',         GetStatisticsComponent::class);
  ORoute::post('/load-articulo',          LoadArticuloComponent::class);
  ORoute::post('/save-articulo',          SaveArticuloComponent::class);
});
