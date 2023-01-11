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
					$factura->set('id_cliente',       $data->getIdCliente());
					$factura->set('nombre_apellidos', $datos['nombre_apellidos']);
					$factura->set('dni_cif',          $datos['dni_cif']);
					$factura->set('telefono',         $datos['telefono']);
					$factura->set('email',            $datos['email']);
					$factura->set('direccion',        $datos['direccion']);
					$factura->set('codigo_postal',    $datos['codigo_postal']);
					$factura->set('poblacion',        $datos['poblacion']);
					$factura->set('provincia',        $datos['provincia']);
					$factura->set('importe',          0);
					$factura->set('impresa',          $data->getImprimir());
					$factura->save();

					// Actualizo las ventas de la factura y recalculo el importe
					$importe = $this->clientes_service->updateFacturaVentas($factura->get('id'), $data->getVentas());
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
