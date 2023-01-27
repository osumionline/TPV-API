<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidoDTO;
use OsumiFramework\App\Model\Pedido;
use OsumiFramework\App\Model\LineaPedido;
use OsumiFramework\App\Model\CodigoBarras;
use OsumiFramework\App\Model\VistaPedido;
use OsumiFramework\App\Model\Articulo;

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

			$recepcionado = $pedido->get('recepcionado');

			if (!$recepcionado) {
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
			}

			// Guardo datos del pedido
			$pedido->set('id_proveedor', $data->getIdProveedor());
			$pedido->set('metodo_pago', $data->getIdMetodoPago());
			$pedido->set('tipo', $data->getTipo());
			$pedido->set('num', $data->getNum());
			$pedido->set('importe', $data->getImporte());
			$pedido->set('portes', $data->getPortes());
			$pedido->set('descuento', $data->getDescuento());
			if (!is_null($data->getFechaPago())) {
				$pedido->set('fecha_pago', urldecode($data->getFechaPago()), '%e/%c/%Y');
			}
			else {
				$pedido->set('fecha_pago', null);
			}
			if (!is_null($data->getFechaPedido())) {
				$pedido->set('fecha_pedido', urldecode($data->getFechaPedido()), '%e/%c/%Y');
			}
			else {
				$pedido->set('fecha_pedido', null);
			}
			$pedido->set('re', $data->getRe());
			$pedido->set('europeo', $data->getUe());
			$pedido->set('faltas', $data->getFaltas());
			$pedido->set('recepcionado', $data->getRecepcionado());
			if ($data->getRecepcionado() && is_null($pedido->get('fecha_recepcionado'))) {
				$pedido->set('fecha_recepcionado', date('Y-m-d H:i:s', time()));
			}
			if (!is_null($data->getObservaciones())) {
				$pedido->set('observaciones', urldecode($data->getObservaciones()));
			}

			$pedido->save();
			$data->setId($pedido->get('id'));

			if (!$recepcionado) {
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
					$lp->set('re', $data->getRe() ? $linea['re'] : null);
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
						$articulo->set('re', $data->getRe() ? $linea['re'] : null);
						$articulo->save();

						$lp->set('nombre_articulo', $articulo->get('nombre'));
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

			// Actualizo PDFs adjuntos
			$this->compras_service->updatePedidoPDFs($pedido, $data->getPdfs());
		}

		$this->getTemplate()->add('status',  $status);
		$this->getTemplate()->add('id',      empty($data->getId()) ? 'null' : $data->getId());
		$this->getTemplate()->add('message', $message);
	}
}
