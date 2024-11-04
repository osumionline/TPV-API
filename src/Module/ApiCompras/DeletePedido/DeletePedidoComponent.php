<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiCompras\DeletePedido;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Pedido;

class DeletePedidoComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar un pedido
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
			$pedido = Pedido::findOne(['id' => $id]);
			if (!is_null($pedido)) {
				$pedido->deleteFull();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
