<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\App\DTO\PedidoDTO;

#[OModuleAction(
	url: '/auto-save-pedido',
	services: ['compras']
)]
class autoSavePedidoAction extends OAction {
	/**
	 * Función para auto-guardar un pedido
	 *
	 * @param PedidoDTO $data Objeto con toda la información sobre un pedido
	 * @return void
	 */
	public function run(PedidoDTO $data):void {
		$pedido = new Pedido();
		if (!is_null($data->getId())) {
			$pedido->find(['id' => $data->getId()]);
		}

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

		$this->getTemplate()->add('id', $data->getId());
	}
}
