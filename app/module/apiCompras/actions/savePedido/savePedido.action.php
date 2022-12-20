<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidoDTO;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Model\LineaPedido;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\VistaPedido;

#[OModuleAction(
	url: '/save-pedido',
	services: ['compras']
)]
class savePedidoAction extends OAction {
	/**
	 * Función para guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data):void {
		$status  = 'ok';
		$message = '';

		if (!$data->isValid()) {
			$status = 'error';
		}

		if ($status == 'ok') {
			$pedido = new Pedido();
			if (!is_null($data->getId())) {
				$pedido->find(['id' => $data->getId()]);
			}

			// Si el pedido ya está recepcionado devuelvo error por que ya no se puede volver a modificar
			if ($pedido->get('recepcionado')) {
				$status = 'error';
			}

			if ($status == 'ok') {
				// Compruebo posibles códigos de barras
				$cb_errors = [];
				foreach ($data->getLineas() as $linea) {
					if (!is_null($linea['codBarras'])) {
						$cb = new CodigoBarras();
						if ($cb->find(['codigo_barras' => $linea['codBarras']])) {
							array_push($cb_errors, $linea['codBarras'].' ('.urldecode($linea['nombreArticulo']).')');
						}
					}
				}

				if (count($cb_errors) > 0) {
					$status = 'error';
					$message = implode(', ', $cb_errors);
				}

				if ($status == 'ok') {
					// Guardo datos del pedido
					$pedido->set('id_proveedor', $data->getIdProveedor());
					$pedido->set('albaran_factura', $data->getAlbaranFactura() === 'albaran');
					$pedido->set('num_albaran_factura', $data->getNumAlbaranFactura());
					$pedido->set('importe', $data->getImporte());
					$pedido->set('portes', $data->getPortes());
					$pedido->set('fecha_pago', urldecode($data->getFechaPago()), '%e/%c/%Y');
					$pedido->set('fecha_pedido', urldecode($data->getFechaPedido()), '%e/%c/%Y');
					$pedido->set('re', $data->getRe());
					$pedido->set('europeo', $data->getUe());
					$pedido->set('faltas', $data->getFaltas());
					$pedido->set('recepcionado', $data->getRecepcionado());
					$pedido->set('observaciones', urldecode($data->getObservaciones()));

					$pedido->save();
					$data->setId($pedido->get('id'));

					// Borro líneas del pedido
					$this->compras_service->borrarLineasPedido($pedido->get('id'));

					// Guardo nuevas líneas del pedido
					foreach ($data->getLineas() as $linea) {
						$lp = new LineaPedido();
						$lp->set('id_pedido', $pedido->get('id'));
						$lp->set('id_articulo', $linea['idArticulo']);
						$lp->set('codigo_barras', $linea['codBarras']);
						$lp->set('unidades', $linea['unidades']);
						$lp->set('palb', $linea['palb']);
						$lp->set('pvp', $linea['pvp']);
						$lp->set('iva', $linea['iva']);
						$lp->set('re', $linea['re']);
						$lp->set('descuento', $linea['descuento']);
						$lp->save();

						// Si el pedido está recepcionado
						if ($pedido->get('recepcionado')) {
							$articulo = new Articulo();
							$articulo->find(['id' => $linea['idArticulo']]);
							$articulo->set('stock', $articulo->get('stock') + $linea['unidades']);
							$articulo->set('palb', $linea['palb']);
							$articulo->set('pvp', $linea['pvp']);
							$articulo->set('iva', $linea['iva']);
							$articulo->set('re', $linea['re']);
							$articulo->save();

							$lp->set('nombre_articulo', $art->get('nombre'));
							$lp->save();

							// Si viene un nuevo código de barras se lo creo
							if (!is_null($linea['codBarras'])) {
								$cb = new CodigoBarras();
								$cb->set('id_articulo', $linea['idArticulo']);
								$cb->set('codigo_barras', $linea['codBarras']);
								$cb->set('por_defecto', false);
								$cb->save();
							}

							// TODO: falta event log
						}
					}

					// Borro todas las líneas de la vista del pedido
					$this->compras_service->borrarVistaPedido($pedido->get('id'));

					// Guardo nueva vista
					foreach ($data->getVista() as $vista) {
						$vp = new VistaPedido();
						$vp->set('id_pedido', $pedido->get('id'));
						$vp->set('id_column', $vista['idColumn']);
						$vp->set('status', $vista['status']);
						$vp->save();
					}
				}

				// Actualizo PDFs adjuntos
				$this->compras_service->updatePedidoPDFs($pedido, $data->getPdfs());
			}
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('id',      empty($data->getId()) ? 'null' : $data->getId());
		$this->getTemplate()->add('message', $message);
	}
}
