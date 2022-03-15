<?php declare(strict_types=1);

namespace OsumiFramework\App\Module;

use OsumiFramework\OFW\Core\OModule;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Routing\ORoute;

#[ORoute(
	type: 'json',
	prefix: '/api-ventas'
)]
class apiVentas extends OModule {
	/**
	 * Función para guardar una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	#[ORoute('/save-venta')]
	public function saveVenta(ORequest $req): void {
		
	}
}
