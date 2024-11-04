<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCompras\GetPedidos;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ComprasService;
use Osumi\OsumiFramework\App\DTO\PedidosFilterDTO;
use Osumi\OsumiFramework\App\Component\Model\PedidoList\PedidoListComponent;

class GetPedidosComponent extends OComponent {
	private ?ComprasService $cs = null;

  public string $status = 'ok';
	public ?PedidoListComponent $guardados     = null;
	public ?PedidoListComponent $recepcionados = null;
	public float $guardados_pags     = 0;
	public float $recepcionados_pags = 0;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ComprasService::class);
		$this->guardados     = new PedidoListComponent();
		$this->recepcionados = new PedidoListComponent();
  }

	/**
	 * Función para obtener el listado de pedidos
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 * @return void
	 */
	public function run(PedidosFilterDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$pedidos = $this->cs->getPedidos($data);

			$this->guardados->list     = $pedidos['guardados'];
			$this->recepcionados->list = $pedidos['recepcionados'];
			$this->guardados_pags      = $pedidos['guardados_pags'];
			$this->recepcionados_pags  = $pedidos['recepcionados_pags'];
		}
	}
}
