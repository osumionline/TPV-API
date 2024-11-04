<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiVentas\GetLineasTicket;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\LineaVenta;
use Osumi\OsumiFramework\App\Component\Model\LineaVentaList\LineaVentaListComponent;

class GetLineasTicketComponent extends OComponent {
	public string $status = 'ok';
	public ?LineaVentaListComponent $list = null;

	/**
	 * Función para obtener el detalle de unas líneas de ticket concretas
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$lineas = $req->getParamString('lineas');
		$this->list = new LineaVentaListComponent();

		if (is_null($lineas)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$lineas_list = explode(',', $lineas);
			$list = [];

			foreach ($lineas_list as $linea) {
				$list[] = LineaVenta::findOne(['id' => $linea]);
			}

			$this->list->list = $list;
		}
	}
}
