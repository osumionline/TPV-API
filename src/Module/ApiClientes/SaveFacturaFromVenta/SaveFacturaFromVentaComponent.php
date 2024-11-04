<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SaveFacturaFromVenta;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\Model\Venta;
use Osumi\OsumiFramework\App\Model\Factura;

class SaveFacturaFromVentaComponent extends OComponent {
  private ?ClientesService $cs = null;

  public string       $status = 'ok';
  public string | int $id     = 'null';

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
  }

	/**
	 * Función para crear una factura directamente a partir de una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$id_venta = $req->getParamInt('id');
		$this->id = 'null';

		if (is_null($id_venta)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$venta = Venta::findOne(['id' => $id_venta]);
			if (!is_null($venta)) {
				$factura = $venta->getFactura();
				if (!is_null($factura)) {
					if ($factura->impresa) {
						$this->status = 'error-facturada';
					}
					else {
						$this->status = 'error-factura';
					}
					$this->id = $factura->id;
				}

				if ($this->status === 'ok') {
					$cliente = $venta->getCliente();
					if (!is_null($cliente)) {
						$datos = $cliente->getDatosFactura();

						$factura = Factura::create();
            $factura->num_factura      = null;
						$factura->id_cliente       = $cliente->id;
						$factura->nombre_apellidos = $datos['nombre_apellidos'];
						$factura->dni_cif          = $datos['dni_cif'];
						$factura->telefono         = $datos['telefono'];
						$factura->email            = $datos['email'];
						$factura->direccion        = $datos['direccion'];
						$factura->codigo_postal    = $datos['codigo_postal'];
						$factura->poblacion        = $datos['poblacion'];
						$factura->provincia        = $datos['provincia'];
						$factura->importe          = 0;
						$factura->impresa          = false;
						$factura->save();

						// Añado la ventas a la factura y recalculo el importe
						$importe = $this->cs->updateFacturaVentas($factura->id, [$venta->id], false);
						$factura->importe = $importe;
						$factura->save();

						$this->id = $factura->id;
					}
					else {
						$this->status = 'error-cliente';
					}
				}
			}
			else {
				$this->status = 'error-venta';
			}
		}
	}
}
