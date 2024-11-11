<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\ApiInformes\GetInformeCaducidades\GetInformeCaducidadesComponent;
use Osumi\OsumiFramework\App\Module\ApiInformes\GetInformeDetallado\GetInformeDetalladoComponent;
use Osumi\OsumiFramework\App\Module\ApiInformes\GetInformeSimple\GetInformeSimpleComponent;

ORoute::prefix('/api-informes', function() {
  ORoute::post('/get-informe-caducidades', GetInformeCaducidadesComponent::class);
  ORoute::post('/get-informe-detallado',   GetInformeDetalladoComponent::class);
  ORoute::post('/get-informe-simple',      GetInformeSimpleComponent::class);
});
