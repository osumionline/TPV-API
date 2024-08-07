<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\FacturaDTO;
use OsumiFramework\App\Model\Factura;
use OsumiFramework\App\Model\Cliente;

#[OModuleAction(
	url: '/save-factura',
	services: ['clientes']
)]
class saveFacturaAction extends OAction {
	/**
	 * Función para guardar una factura
	 *
	 * @param FacturaDTO $data Objeto con los datos de una factura a guardar
	 * @return void
	 */
	public function run(FacturaDTO $data):void {
		$status = 'ok';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$cliente = new Cliente();
			// Primero busco datos del cliente
			if (!is_null($data->getIdCliente()) && $cliente->find(['id' => $data->getIdCliente()])) {
				$factura = new Factura();

				// Si viene id es una factura que esta siendo editada
				if (!is_null($data->getId())) {
					$factura->find(['id' => $data->getId()]);
				}

				// Si no ha sido impresa se puede editar
				if (!$factura->get('impresa')) {
					$datos = $cliente->getDatosFactura();
					$num_factura = null;
					// Si no estaba impresa y ahora si, le tengo que generar un número de factura
					if ($data->getImprimir()) {
						$num_factura = $this->clientes_service->generateNumFactura();
					}
					$factura = $this->clientes_service->createNewFactura($factura, $num_factura, $data->getIdCliente(), $datos, $data->getImprimir());

					// Actualizo las ventas de la factura y recalculo el importe
					$importe = $this->clientes_service->updateFacturaVentas($factura->get('id'), $data->getVentas(), $data->getImprimir());
					$factura->set('importe', $importe);
					$factura->save();

					$data->setId($factura->get('id'));
				}
				else {
					$status = 'error';
				}
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id', empty($data->getId()) ? 'null' : $data->getId());
	}
}
