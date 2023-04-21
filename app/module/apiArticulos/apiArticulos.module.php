<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Routing\OModule;

#[OModule(
	actions: ['getStatistics', 'deleteArticulo', 'saveArticulo', 'loadArticulo', 'getAccesosDirectos', 'asignarAccesoDirecto', 'deleteAccesoDirecto', 'getHistoricoArticulo'],
	type: 'json',
	prefix: '/api-articulos'
)]
class apiArticulosModule {}
