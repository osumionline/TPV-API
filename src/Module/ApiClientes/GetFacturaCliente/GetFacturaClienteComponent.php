<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\GetFacturaCliente;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Factura;
use Osumi\OsumiFramework\App\Component\Model\Factura\FacturaComponent;

class GetFacturaClienteComponent extends OComponent {
  public string $status = 'ok';
  public ?FacturaComponent $factura = null;

	/**
	 * Función para obtener los datos de una factura concreta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id = $req->getParamInt('id');
		$this->factura = new FacturaComponent();

		if (is_null($id)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$f = Factura::findOne(['id' => $id]);
			if (!is_null($f)) {
				$this->factura->factura = $f;
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
