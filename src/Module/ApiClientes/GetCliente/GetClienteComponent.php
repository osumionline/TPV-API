<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Cliente;
use Osumi\OsumiFramework\App\Component\Model\Cliente\ClienteComponent;

class GetClienteComponent extends OComponent {
  public string $status = 'ok';
  public ?ClienteComponent $cliente = null;

	/**
	 * Función para obtener los detalles de un cliente concreto
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');
		$this->cliente = new ClienteComponent();

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$c = Cliente::findOne(['id' => $id]);
			if (!is_null($c)) {
				$this->cliente->cliente = $c;
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
