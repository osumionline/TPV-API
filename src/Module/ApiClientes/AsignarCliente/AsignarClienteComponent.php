<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\AsignarCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Venta;

class AsignarClienteComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para asignar (o quitar) un cliente a una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id         = $req->getParamInt('id');
		$id_cliente = $req->getParamInt('idCliente');

		if (is_null($id) || is_null($id_cliente)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$venta = Venta::findOne(['id' => $id]);
			if (!is_null($venta)) {
				$venta->id_cliente = $id_cliente;
				$venta->save();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
