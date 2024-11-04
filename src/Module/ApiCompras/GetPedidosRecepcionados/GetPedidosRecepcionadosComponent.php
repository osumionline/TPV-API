<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCompras\GetPedidosRecepcionados;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ComprasService;
use Osumi\OsumiFramework\App\DTO\PedidosFilterDTO;
use Osumi\OsumiFramework\App\Component\Model\PedidoList\PedidoListComponent;

class GetPedidosRecepcionadosComponent extends OComponent {
  private ?ComprasService $cs = null;

  public string $status = 'ok';
  public ?PedidoListComponent $list = null;
  public float $pags = 0;

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ComprasService::class);
    $this->list = new PedidoListComponent();
  }

	/**
	 * Función para obtener el listado de pedidos recepcionados
	 *
	 * @param PedidosFilterDTO $data Objeto con los filtros con los que realizar la busqueda de pedidos
	 * @return void
	 */
	public function run(PedidosFilterDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$pedidos = $this->cs->getPedidosRecepcionados($data);

			$this->list->list = $pedidos['list'];
			$this->pags       = $pedidos['pags'];
		}
	}
}
