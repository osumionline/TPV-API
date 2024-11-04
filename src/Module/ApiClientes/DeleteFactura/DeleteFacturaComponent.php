<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\DeleteFactura;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Factura;

class DeleteFacturaComponent extends OComponent {
  public string $status = 'ok';

	/**
	 * Función para borrar una factura no impresa
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
			$factura = Factura::findOne(['id' => $id]);
			if (!is_null($factura)) {
				$factura->deleteFull();
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
