<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['checkStart', 'saveInstallation', 'openBox', 'getTiposPago', 'deleteTipoPago', 'saveTipoPago', 'saveTipoPagoOrden', 'getSalidasCaja', 'saveSalidaCaja', 'deleteSalidaCaja', 'getCierreCaja', 'cerrarCaja', 'syncSale', 'syncStock'],
	type: 'json',
	prefix: '/api'
)]
class apiModule {}
