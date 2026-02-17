<?php

declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\ApiClientes\SaveFactura;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\Service\ClientesService;
use Osumi\OsumiFramework\App\DTO\FacturaDTO;
use Osumi\OsumiFramework\App\Model\Factura;
use Osumi\OsumiFramework\App\Model\Cliente;

class SaveFacturaComponent extends OComponent {
	private ?ClientesService $cs = null;

  public string       $status = 'ok';
	public string | int $id     = 'null';

  public function __construct() {
    parent::__construct();
		$this->cs = inject(ClientesService::class);
  }

	/**
	 * Función para guardar una factura
	 *
	 * @param FacturaDTO $data Objeto con los datos de una factura a guardar
	 * @return void
	 */
	public function run(FacturaDTO $data): void {
		if (!$data->isValid()) {
			$this->status = 'error';
		}

		if ($this->status === 'ok' && (!is_array($this->ventas) || (is_array($this->ventas) && count($this->ventas) < 1))) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$cliente = Cliente::findOne(['id' => $data->idCliente]);
			// Primero busco datos del cliente
			if (!is_null($cliente)) {
				$factura = Factura::create();

				// Si viene id es una factura que esta siendo editada
				if (!is_null($data->id)) {
					$factura = Factura::findOne(['id' => $data->id]);
				}

				// Si no ha sido impresa se puede editar
				if (!$factura->impresa) {
					$datos = $cliente->getDatosFactura();
					$num_factura = null;
					// Si no estaba impresa y ahora si, le tengo que generar un número de factura
					if ($data->imprimir) {
						$num_factura = $this->cs->generateNumFactura();
					}
					$factura = $this->cs->createNewFactura($factura, $num_factura, $data->idCliente, $datos, $data->imprimir);

					// Actualizo las ventas de la factura y recalculo el importe
					$importe = $this->cs->updateFacturaVentas($factura->id, $data->ventas, $data->imprimir);
					$factura->importe = $importe;
					$factura->save();

					$this->id = $factura->id;
				} else {
					$this->status = 'error';
				}
			} else {
				$this->status = 'error';
			}
		}
	}
}
