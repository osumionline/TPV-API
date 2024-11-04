<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetEstadisticasCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Component\Api\UltimasVentas\UltimasVentasComponent;
use Osumi\OsumiFramework\App\Component\Api\TopVentas\TopVentasComponent;

class GetEstadisticasClienteComponent extends OComponent {
	private ?ClientesService $cs = null;

  public string $status = 'ok';
	public ?UltimasVentasComponent $ultimas_ventas = null;
	public ?TopVentasComponent $top_ventas = null;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
		$this->ultimas_ventas = new UltimasVentasComponent();
		$this->top_ventas = new TopVentasComponent();
  }

	/**
	 * Función para obtener las estadísticas de un cliente
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$this->ultimas_ventas->list = $this->cs->getUltimasVentas($id);
			$this->top_ventas->list     = $this->cs->getTopVentas($id);
		}
	}
}
