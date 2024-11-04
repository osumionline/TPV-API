<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\Routes;

use Osumi\OsumiFramework\Routing\ORoute;
use Osumi\OsumiFramework\App\Module\Api\CheckStart\CheckStartComponent;
use Osumi\OsumiFramework\App\Module\Api\CerrarCaja\CerrarCajaComponent;
use Osumi\OsumiFramework\App\Module\Api\DeleteSalidaCaja\DeleteSalidaCajaComponent;
use Osumi\OsumiFramework\App\Module\Api\DeleteTipoPago\DeleteTipoPagoComponent;
use Osumi\OsumiFramework\App\Module\Api\GetCierreCaja\GetCierreCajaComponent;
use Osumi\OsumiFramework\App\Module\Api\GetSalidasCaja\GetSalidasCajaComponent;
use Osumi\OsumiFramework\App\Module\Api\GetTiposPago\GetTiposPagoComponent;
use Osumi\OsumiFramework\App\Module\Api\NewBackup\NewBackupComponent;
use Osumi\OsumiFramework\App\Module\Api\OpenBox\OpenBoxComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveInstallation\SaveInstallationComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveSalidaCaja\SaveSalidaCajaComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveTipoPago\SaveTipoPagoComponent;
use Osumi\OsumiFramework\App\Module\Api\SaveTipoPagoOrden\SaveTipoPagoOrdenComponent;

ORoute::prefix('/api', function() {
  ORoute::post('/check-start',          CheckStartComponent::class);
  ORoute::post('/cerrar-caja',          CerrarCajaComponent::class);
  ORoute::post('/delete-salida-caja',   DeleteSalidaCajaComponent::class);
  ORoute::post('/delete-tipo-pago',     DeleteTipoPagoComponent::class);
  ORoute::post('/get-cierre-caja',      GetCierreCajaComponent::class);
  ORoute::post('/get-salidas-caja',     GetSalidasCajaComponent::class);
  ORoute::post('/get-tipos-pago',       GetTiposPagoComponent::class);
  ORoute::post('/new-backup',           NewBackupComponent::class);
  ORoute::post('/open-box',             OpenBoxComponent::class);
  ORoute::post('/save-installation',    SaveInstallationComponent::class);
  ORoute::post('/save-salida-caja',     SaveSalidaCajaComponent::class);
  ORoute::post('/save-tipo-pago',       SaveTipoPagoComponent::class);
  ORoute::post('/save-tipo-pago-orden', SaveTipoPagoOrdenComponent::class);
});
