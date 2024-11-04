<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCompras\GetPedido;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Pedido;
use Osumi\OsumiFramework\App\Component\Model\Pedido\PedidoComponent;

class GetPedidoComponent extends OComponent {
  public string $status = 'ok';
  public ?PedidoComponent $pedido = null;

	/**
	 * Función para obtener el detalle de un pedido
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');
		$this->pedido = new PedidoComponent();

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$p = Pedido::findOne(['id' => $id]);
			if (!is_null($p)) {
				$this->pedido->pedido = $p;
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
