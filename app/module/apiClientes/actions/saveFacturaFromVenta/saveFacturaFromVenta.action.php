<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Venta;
use OsumiFramework\App\Model\Factura;

#[OModuleAction(
	url: '/save-factura-from-venta',
	services: ['clientes']
)]
class saveFacturaFromVentaAction extends OAction {
	/**
	 * Función para crear una factura directamente a partir de una venta
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status   = 'ok';
		$id_venta = $req->getParamInt('id');
		$id_factura = 'null';

		if (is_null($id_venta)) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$venta = new Venta();
			if ($venta->find(['id' => $id_venta])) {
				$factura = $venta->getFactura();
				if (!is_null($factura)) {
					if ($factura->get('impresa')) {
						$status = 'error-facturada';
					}
					else {
						$status = 'error-factura';
					}
					$id_factura = $factura->get('id');
				}

				if ($status == 'ok') {
					$cliente = $venta->getCliente();
					if (!is_null($cliente)) {
						$datos = $cliente->getDatosFactura();

						$factura = new Factura();
						$factura->set('num_factura',      null);
						$factura->set('id_cliente',       $cliente->get('id'));
						$factura->set('nombre_apellidos', $datos['nombre_apellidos']);
						$factura->set('dni_cif',          $datos['dni_cif']);
						$factura->set('telefono',         $datos['telefono']);
						$factura->set('email',            $datos['email']);
						$factura->set('direccion',        $datos['direccion']);
						$factura->set('codigo_postal',    $datos['codigo_postal']);
						$factura->set('poblacion',        $datos['poblacion']);
						$factura->set('provincia',        $datos['provincia']);
						$factura->set('importe',          0);
						$factura->set('impresa',          false);
						$factura->save();

						// Añado la ventas a la factura y recalculo el importe
						$importe = $this->clientes_service->updateFacturaVentas($factura->get('id'), [$venta->get('id')], false);
						$factura->set('importe', $importe);
						$factura->save();

						$id_factura = $factura->get('id');
					}
					else {
						$status = 'error-cliente';
					}
				}
			}
			else {
				$status = 'error-venta';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id_factura);
	}
}
